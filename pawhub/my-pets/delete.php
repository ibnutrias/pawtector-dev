<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: /");
    exit;
}

require_once "../../core/koneksi.php";

$pet_id = $_GET['id'] ?? null;
if ($pet_id) {
    $owner_id = $_SESSION['user_id'];
    // Delete only if owned by user
    $stmt = $koneksi->prepare("DELETE FROM pets WHERE id = ? AND owner_id = ?");
    $stmt->bind_param("ii", $pet_id, $owner_id);
    $stmt->execute();
    $stmt->close();
}

header("Location: /pawhub/my-pets");
exit;
?>