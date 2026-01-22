<?php
session_start();
include('../includes/connect.php');

// Only Admins allowed
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

// Fetch all events
$result = $conn->query("SELECT * FROM events ORDER BY event_date ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Events</title>
  <link rel="stylesheet" href="../css/manage_events.css">
</head>
<body>
  <div class="admin-container">
    <h2>Manage Events</h2>
    <a href="add_event.php" class="btn">+ Add New Event</a>
    <a href="admin_dashboard.php" class="btn-secondary">Back to Dashboard</a>

    <table>
      <tr>
        <th>ID</th>
        <th>Event Name</th>
        <th>Description</th>
        <th>Date</th>
        <th>Time</th>
        <th>Image</th>
        <th>Actions</th>
      </tr>
      <?php if ($result && $result->num_rows > 0) { ?>
        <?php while ($row = $result->fetch_assoc()) { ?>
          <tr>
            <td><?php echo $row['event_id']; ?></td>
            <td><?php echo htmlspecialchars($row['event_name']); ?></td>
            <td><?php echo htmlspecialchars($row['event_des']); ?></td>
            <td><?php echo $row['event_date']; ?></td>
            <td><?php echo $row['event_time']; ?></td>
            <td>
              <?php if (!empty($row['image_event'])) { ?>
                <img src="../<?php echo htmlspecialchars($row['image_event']); ?>" width="80" style="border-radius:6px;">
              <?php } else { echo "No Image"; } ?>
            </td>
            <td>
              <a href="../admin/edit_events.php?id=<?php echo $row['event_id']; ?>" class="btn-small">Edit</a>
              <a href="../admin/delete_event.php?id=<?php echo $row['event_id']; ?>" 
                 class="btn-small btn-danger"
                 onclick="return confirm('Are you sure you want to delete this event?')">Delete</a>
            </td>
          </tr>
        <?php } ?>
      <?php } else { ?>
        <tr><td colspan="7" style="text-align:center;">No events found.</td></tr>
      <?php } ?>
    </table>
  </div>
</body>
</html>
