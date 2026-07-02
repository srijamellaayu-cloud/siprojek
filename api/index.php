<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "DEBUG: api/index.php is executing...<br>";

if (!file_exists(__DIR__ . '/../public/index.php')) {
    die("DEBUG ERROR: public/index.php not found!");
}

echo "DEBUG: public/index.php found. Attempting to require it...<br>";
require __DIR__ . '/../public/index.php';
