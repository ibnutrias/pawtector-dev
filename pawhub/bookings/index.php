<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: /");
    exit;
}
require_once "../../core/koneksi.php";
include_once '../../core/page.php';

page_start('Pawhub');
include_once "../navbar.php";
echo "bookings";

?>



<?php
page_end();
?>