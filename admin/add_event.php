<?php
session_start();
include('../includes/connect.php');

// Only Admins allowed
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_name = trim($_POST['event_name']);
    $event_des  = trim($_POST['event_des']);
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];

    // Handle image upload
    $image_path = null;
    if (!empty($_FILES['image_event']['name'])) {
        $upload_dir = "../includes/uploads/events/"; // folder for events
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $newName = time() . "_" . basename($_FILES['image_event']['name']);
        $target_file = $upload_dir . $newName;

        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (in_array($_FILES['image_event']['type'], $allowed)) {
            if (move_uploaded_file($_FILES['image_event']['tmp_name'], $target_file)) {
                $image_path = "includes/uploads/events/" . $newName; // relative path
            }
        }
    }

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO events (event_name, event_des, event_date, event_time, image_event) 
                            VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $event_name, $event_des, $event_date, $event_time, $image_path);

    if ($stmt->execute()) {
        echo "<script>alert('Event added successfully!'); window.location='manage_events.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add New Event</title>
  <link rel="stylesheet" href="../css/add_event.css"> <!-- your CSS -->
</head>
<body>
  <div class="admin-container">
    <h2>Add New Event</h2>
    <form method="POST" enctype="multipart/form-data">
      <label>Event Name</label>
      <input type="text" name="event_name" required>

      <label>Event Description</label>
      <textarea name="event_des" rows="4" required></textarea>

      <label>Event Date</label>
      <input type="date" name="event_date" required>

      <label>Event Time</label>
      <input type="time" name="event_time" required>

      <label>Event Image</label>
      <input type="file" name="image_event" accept="image/*">

      <div class="actions">
        <button type="submit">Add Event</button>
        <a href="admin_dashboard.php" class="btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</body>
</html>
