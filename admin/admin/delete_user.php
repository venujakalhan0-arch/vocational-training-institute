<?php
session_start();
include('../includes/connect.php');

// Only Admin can delete
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    exit("Access denied");
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Prevent deleting admins (safety)
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id=? AND role IN ('Instructor','Student')");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: manage_users.php");
exit();
