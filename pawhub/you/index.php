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

// 1. Fetch current user data
$user_id = $_SESSION['user_id'];
$query = "SELECT fullname, email, password, created_at FROM users WHERE id = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    // Should not happen if session is valid, but good safety
    session_destroy();
    header("Location: /");
    exit;
}

$fullname = $user['fullname'];
$email = $user['email'];
$date_created = date("M d, Y", strtotime($user['created_at']));
$stored_hash = $user['password'];

// Fetch actual pet count
$stmt_count = $koneksi->prepare("SELECT COUNT(*) as count FROM pets WHERE owner_id = ?");
$stmt_count->bind_param("i", $user_id);
$stmt_count->execute();
$res_count = $stmt_count->get_result();
$row_count = $res_count->fetch_assoc();
$pet_count = $row_count['count'] ?? 0;
$stmt_count->close();

// Initialize messages
$success_msg = "";
$error_msg = "";

// 2. Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_fullname = htmlspecialchars(trim($_POST['fullname']));
    $new_email = htmlspecialchars(trim($_POST['email']));
    $new_password = $_POST['password'];

    // Check for changes
    $has_changes = false;
    $updates = [];
    $types = "";
    $params = [];

    if ($new_fullname !== $fullname) {
        $updates[] = "fullname = ?";
        $types .= "s";
        $params[] = $new_fullname;
        $has_changes = true;
    }

    if ($new_email !== $email) {
        $updates[] = "email = ?";
        $types .= "s";
        $params[] = $new_email;
        $has_changes = true;
    }

    if (!empty($new_password)) {
        // Only update password if not empty
        // Verify it's actually different (optional, but hashing makes it hard to compare directly without recalc)
        // For simplicity, if they typed something, we update it.
        $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $updates[] = "password = ?";
        $types .= "s";
        $params[] = $new_hash;
        $has_changes = true;
    }

    if ($has_changes) {
        $query_update = "UPDATE users SET " . implode(", ", $updates) . " WHERE id = ?";
        $types .= "i";
        $params[] = $user_id;

        $stmt_update = $koneksi->prepare($query_update);
        $stmt_update->bind_param($types, ...$params);

        if ($stmt_update->execute()) {
            $success_msg = "Profil berhasil diperbarui.";
            // Update local variables to reflect changes immediately
            if ($new_fullname !== $fullname) {
                $_SESSION['fullname'] = $new_fullname;
                $fullname = $new_fullname;
            }
            if ($new_email !== $email) {
                $_SESSION['email'] = $new_email;
                $email = $new_email;
            }
        } else {
            $error_msg = "Gagal memperbarui profil: " . $stmt_update->error;
        }
        $stmt_update->close();
    } else {
        $error_msg = "Tidak ada perubahan data.";
    }
}

page_start('Pawhub');
include_once "../navbar.php";
include_once "section-profil.php";
?>


<?php
page_end();
?>