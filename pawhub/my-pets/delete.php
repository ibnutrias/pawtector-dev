<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: " . url('/'));
    exit;
}

require_once "../../core/koneksi.php";

if (!isset($_GET['id'])) {
    header("Location: " . url('pawhub/my-pets'));
    exit;
}

$petId = intval($_GET['id']);
$ownerId = $_SESSION['user_id'];

// Check ownership
$check = $koneksi->prepare("SELECT id FROM pets WHERE id = ? AND owner_id = ?");
$check->bind_param("ii", $petId, $ownerId);
$check->execute();
$res = $check->get_result();

if ($res->num_rows > 0) {
    // Delete
    $del = $koneksi->prepare("DELETE FROM pets WHERE id = ?");
    $del->bind_param("i", $petId);
    $del->execute();
}

header("Location: " . url('pawhub/my-pets'));
exit;
?>