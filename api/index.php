<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Register shutdown function to catch fatal errors (e.g. Class not found)
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        echo "<h3>FATAL ERROR CAUGHT:</h3>";
        echo "<strong>Message:</strong> " . $error['message'] . "<br>";
        echo "<strong>File:</strong> " . $error['file'] . " on line " . $error['line'] . "<br>";
    }
});

echo "DEBUG: api/index.php is executing...<br>";

if (!file_exists(__DIR__ . '/../public/index.php')) {
    die("DEBUG ERROR: public/index.php not found!");
}

echo "DEBUG: public/index.php found. Attempting to require it...<br>";

try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    echo "<h3>FATAL EXCEPTION CAUGHT:</h3>";
    echo "<strong>Message:</strong> " . $e->getMessage() . "<br>";
    echo "<strong>File:</strong> " . $e->getFile() . " on line " . $e->getLine() . "<br>";
    echo "<strong>Stack trace:</strong><pre>" . $e->getTraceAsString() . "</pre>";
}
