<?php

$DB_SERVER = "127.0.0.1";
$DB_USERNAME = "root";
$DB_PASSWORD = "";
$DB_DATABASE = "pawpark";

ini_set('display_errors', 1);
error_reporting(E_ALL);
// $koneksi = mysqli_connect($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
$koneksi = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
if ($koneksi->connect_error) {
    echo "Error database:" . $koneksi->connect_error . "<br>";
}

// --- DYNAMIC BASE URL (Simple & Readable) ---
if (!function_exists('base_url')) {
    function base_url($path = '')
    {
        // 1. Get Project Root (one level up from this file)
        $projectRoot = dirname(__DIR__); // e.g., C:/xampp/htdocs/pawpark

        // 2. Get Server Document Root
        $docRoot = $_SERVER['DOCUMENT_ROOT']; // e.g., C:/xampp/htdocs

        // 3. Subtract Document Root from Project Root to get URL path
        // Normalize slashes to forward slashes for consistency
        $root = str_replace('\\', '/', $projectRoot);
        $doc = str_replace('\\', '/', $docRoot);

        $base = str_replace($doc, '', $root);

        return $base . '/' . ltrim($path, '/');
    }
}

// Alias for easier use
if (!function_exists('url')) {
    function url($path = '')
    {
        return base_url($path);
    }
}