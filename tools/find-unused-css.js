import fs from 'fs';
import path from 'path';

const root = path.resolve(process.cwd());
const cssPath = path.join(root, 'resources', 'css', 'custom.css');

function readFileSafe(p) {
    try { return fs.readFileSync(p, 'utf8'); } catch { return null; }
}

const cssText = readFileSafe(cssPath);
if (!cssText) {
    console.error('custom.css not found at', cssPath);
    process.exit(2);
}

// Extract simple class selectors from CSS (dot followed by identifier)
const matches = cssText.match(/\.([A-Za-z_][-A-Za-z0-9_]*)/g) || [];
const classes = [...new Set(matches.map(m => m.slice(1)))].sort();

// Directories to search for usage
const searchDirs = [
    path.join(root, 'resources', 'views'),
    path.join(root, 'resources', 'js'),
    path.join(root, 'resources', 'css'),
    path.join(root, 'app'),
    path.join(root, 'public')
];

function walk(dir) {
    const files = [];
    if (!fs.existsSync(dir)) return files;
    for (const entry of fs.readdirSync(dir, { withFileTypes: true })) {
        const p = path.join(dir, entry.name);
        if (entry.isDirectory()) files.push(...walk(p));
        else files.push(p);
    }
    return files;
}

const allFiles = [];
for (const d of searchDirs) allFiles.push(...walk(d));

// Exclude binary / node_modules etc
const textFiles = allFiles.filter(f => {
    const ext = path.extname(f).toLowerCase();
    return ['.php', '.blade.php', '.html', '.js', '.vue', '.css', '.json', '.ts'].includes(ext) || f.endsWith('.blade.php');
}).filter(f => path.resolve(f) !== path.resolve(cssPath));

const usage = {};
for (const cls of classes) {
    usage[cls] = 0;
    const re = new RegExp('\\b' + cls + '\\b', 'g');
    for (const f of textFiles) {
        const txt = readFileSafe(f);
        if (!txt) continue;
        if (re.test(txt)) usage[cls]++;
    }
}

const unused = Object.entries(usage).filter(([, c]) => c === 0).map(([k]) => k);

const report = {
    total_classes: classes.length,
    scanned_files: textFiles.length,
    unused_count: unused.length,
    unused: unused.slice(0, 300),
};

console.log(JSON.stringify(report, null, 2));

// Write report to file for review
fs.writeFileSync(path.join(root, 'tmp-unused-css-report.json'), JSON.stringify(report, null, 2));
console.log('Report written to tmp-unused-css-report.json');
