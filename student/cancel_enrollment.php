<?php
session_start();
include('../includes/connect.php');

// Ensure only students can cancel
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Student') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['course_id'])) {
    $course_id = intval($_GET['course_id']);

    // Delete from enrollments table
    $stmt = $conn->prepare("DELETE FROM enrollments WHERE user_id = ? AND course_id = ?");
    $stmt->bind_param("ii", $user_id, $course_id);

    if ($stmt->execute()) {
        echo "<script>alert('Enrollment canceled successfully!'); window.location='my_enrollments.php';</script>";
    } else {
        echo "<script>alert('Error canceling enrollment. Please try again.'); window.location='my_enrollments.php';</script>";
    }

    $stmt->close();
} else {
    header("Location: my_enrollments.php");
    exit();
}
?>
