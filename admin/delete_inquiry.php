<?php
session_start();
include('../includes/connect.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../includes/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'] ?? '';
$inquiry_id = $_GET['id'] ?? null;

// Validate inquiry ID
if (!$inquiry_id || !is_numeric($inquiry_id)) {
    echo "<script>alert('Invalid inquiry ID'); window.history.back();</script>";
    exit();
}

// Check if inquiry exists
$stmt = $conn->prepare("SELECT user_id FROM inquiries WHERE inquiry_id = ?");
$stmt->bind_param("i", $inquiry_id);
$stmt->execute();
$result = $stmt->get_result();
$inquiry = $result->fetch_assoc();
$stmt->close();

if (!$inquiry) {
    echo "<script>alert('Inquiry not found!'); window.history.back();</script>";
    exit();
}

// Only allow admin or the user who submitted it
if ($role !== 'Admin' && $inquiry['user_id'] != $user_id) {
    echo "<script>alert('Unauthorized action!'); window.history.back();</script>";
    exit();
}

// Delete inquiry
$delete_stmt = $conn->prepare("DELETE FROM inquiries WHERE inquiry_id = ?");
$delete_stmt->bind_param("i", $inquiry_id);

if ($delete_stmt->execute()) {
    echo "<script>alert('Inquiry deleted successfully!'); window.location.href='inquiry.php';</script>";
} else {
    echo "<script>alert('Error deleting inquiry. Please try again.'); window.history.back();</script>";
}

$delete_stmt->close();
$conn->close();
?>
