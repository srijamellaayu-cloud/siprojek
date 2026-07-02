// Vendor assets loaded via CDN fallback to avoid requiring npm install
function loadScript(src) {
    return new Promise((resolve, reject) => {
        const s = document.createElement('script');
        s.src = src;
        s.async = false;
        s.onload = () => resolve();
        s.onerror = () => reject(new Error('Failed to load ' + src));
        document.head.appendChild(s);
    });
}

function loadStyle(href) {
    return new Promise((resolve, reject) => {
        const l = document.createElement('link');
        l.rel = 'stylesheet';
        l.href = href;
        l.onload = () => resolve();
        l.onerror = () => reject(new Error('Failed to load ' + href));
        document.head.appendChild(l);
    });
}

async function loadVendors() {
    // Load JS in order: jQuery -> Bootstrap bundle -> AdminLTE
    try {
        await loadScript('https://code.jquery.com/jquery-3.6.0.min.js');
        window.$ = window.jQuery = window.jQuery || window.$;
        await loadScript('https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js');
        await loadScript('https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js');
    } catch (e) {
        console.warn('Vendor load failed', e);
    }
}

// NOTE: don't statically import app CSS here — load compiled build CSS after vendor CSS
// to ensure vendor styles don't override our custom rules.

// Initialize vendors and app behavior after DOM ready
document.addEventListener('DOMContentLoaded', async () => {
    await loadVendors();

    // Initialize Bootstrap tooltips if available
    try {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            if (window.bootstrap && typeof window.bootstrap.Tooltip === 'function') {
                return new window.bootstrap.Tooltip(tooltipTriggerEl);
            }
            return null;
        });
    } catch (e) {
        // ignore
    }
});

import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
