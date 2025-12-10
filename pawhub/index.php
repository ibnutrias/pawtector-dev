<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: /");
    exit;
}
require_once "../core/koneksi.php";
include_once '../core/page.php';


page_start('Pawhub');
header("Location: /pawhub/bookings");
page_end();
?>