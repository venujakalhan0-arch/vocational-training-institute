<?php
session_start();
include('../includes/connect.php');

// Only Admins
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Event ID.");
}
$event_id = (int)$_GET['id'];

// Delete event
$stmt = $conn->prepare("DELETE FROM events WHERE event_id=?");
$stmt->bind_param("i", $event_id);

if ($stmt->execute()) {
    echo "<script>alert('Event deleted successfully!'); window.location='manage_events.php';</script>";
} else {
    echo "Error: " . $stmt->error;
}
$stmt->close();
?>
