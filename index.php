<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once 'core/page.php';
include_once 'beranda/utama.php';
?>