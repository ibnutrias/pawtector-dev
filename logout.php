<?php
session_start();
echo isset($_SESSION['user_id']);
// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    echo "Logged out";
    session_destroy();
    header("Location: /");
} else {
    echo "asdsad";
    header("Location: /");
}
?>