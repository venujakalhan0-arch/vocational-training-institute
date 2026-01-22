<?php
session_start();
include('../includes/connect.php');

// Only Admins allowed
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

// Validate course ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid course ID.");
}
$course_id = (int)$_GET['id'];

// Fetch image path before deleting
$stmt = $conn->prepare("SELECT image FROM courses WHERE course_id=?");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$stmt->bind_result($image);
$stmt->fetch();
$stmt->close();

// Delete the course
$stmt = $conn->prepare("DELETE FROM courses WHERE course_id=?");
$stmt->bind_param("i", $course_id);
if ($stmt->execute()) {
    // Remove image file if it exists
    if (!empty($image) && file_exists("../" . $image)) {
        unlink("../" . $image);
    }
    echo "<script>alert('Course deleted successfully!'); window.location='manage_course.php';</script>";
} else {
    echo "<script>alert('Error deleting course.'); window.location='manage_course.php';</script>";
}
$stmt->close();
$conn->close();
?>
