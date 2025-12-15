<?php
session_start();
require_once 'core/koneksi.php';
// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    session_destroy();
    header("Location: " . url('/'));
    exit;
} else {
    header("Location: " . url('/'));
    exit;
}
?>