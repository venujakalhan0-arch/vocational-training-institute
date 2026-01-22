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

// Fetch event
$stmt = $conn->prepare("SELECT * FROM events WHERE event_id=?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$event) die("Event not found.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_name = trim($_POST['event_name']);
    $event_des  = trim($_POST['event_des']);
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];

    $image_path = $event['image_event'];
    if (!empty($_FILES['image_event']['name'])) {
        $upload_dir = "../includes/uploads/events/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $newName = time() . "_" . basename($_FILES['image_event']['name']);
        $target_file = $upload_dir . $newName;

        if (move_uploaded_file($_FILES['image_event']['tmp_name'], $target_file)) {
            $image_path = "includes/uploads/events/" . $newName;
        }
    }

    $stmt = $conn->prepare("UPDATE events SET event_name=?, event_des=?, event_date=?, event_time=?, image_event=? WHERE event_id=?");
    $stmt->bind_param("sssssi", $event_name, $event_des, $event_date, $event_time, $image_path, $event_id);

    if ($stmt->execute()) {
        echo "<script>alert('Event updated successfully!'); window.location='manage_events.php';</script>";
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
  <title>Edit Event</title>
  <link rel="stylesheet" href="../css/add_event.css">
</head>
<body>
  <div class="admin-container">
    <h2>Edit Event</h2>
    <form method="POST" enctype="multipart/form-data">
      <label>Event Name</label>
      <input type="text" name="event_name" value="<?php echo htmlspecialchars($event['event_name']); ?>" required>

      <label>Event Description</label>
      <textarea name="event_des" rows="4" required><?php echo htmlspecialchars($event['event_des']); ?></textarea>

      <label>Event Date</label>
      <input type="date" name="event_date" value="<?php echo $event['event_date']; ?>" required>

      <label>Event Time</label>
      <input type="time" name="event_time" value="<?php echo $event['event_time']; ?>" required>

      <label>Current Image</label><br>
      <?php if ($event['image_event']) { ?>
        <img src="../<?php echo htmlspecialchars($event['image_event']); ?>" width="100"><br>
      <?php } else { echo "No image"; } ?>
      <input type="file" name="image_event" accept="image/*"><br><br>

      <div class="actions">
        <button type="submit">Update Event</button>
        <a href="manage_events.php" class="btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</body>
</html>
