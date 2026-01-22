<?php
session_start();
include('../includes/connect.php');

// Only logged-in students
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Student') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Validate course ID
if (!isset($_GET['course_id']) || !is_numeric($_GET['course_id'])) {
    die("Invalid Course ID");
}

$course_id = (int)$_GET['course_id'];

// Check if already enrolled
$check = $conn->prepare("SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?");
$check->bind_param("ii", $user_id, $course_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo "<script>alert('You are already enrolled in this course!'); window.location='../student/my_enrollments.php';</script>";
    exit();
}
$check->close();

// Enroll student
$stmt = $conn->prepare("INSERT INTO enrollments (user_id, course_id) VALUES (?, ?)");
$stmt->bind_param("ii", $user_id, $course_id);

if ($stmt->execute()) {
    echo "<script>alert('Enrollment successful!'); window.location='../student/my_enrollments.php';</script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
