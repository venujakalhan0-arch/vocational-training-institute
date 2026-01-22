<?php
session_start();
include('../includes/connect.php');

// Only Admins allowed
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../includes/login.php");
    exit();
}

// Fetch inquiries
$query = "SELECT * FROM inquiries ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Inquiries</title>
  <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
  <div class="admin-container">
    <h2>Manage Inquiries</h2>
    
    <!-- ✅ Back Button -->
    <a href="admin_dashboard.php" class="btn" style="margin-bottom: 15px;">← Back to Admin Dashboard</a>

    <?php if ($result && $result->num_rows > 0) { ?>
      <table>
        <tr>
          <th>ID</th>
          <th>Student ID</th>
          <th>Message</th>
          <th>Reply</th>
          <th>Submitted At</th>
          <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
          <tr>
            <td><?php echo $row['inquiry_id']; ?></td>
            <td><?php echo htmlspecialchars($row['user_id']); ?></td>
            <td><?php echo htmlspecialchars($row['message']); ?></td>
            <td><?php echo htmlspecialchars($row['reply']); ?></td>
            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
            <td>
              <a href="reply_inquiry.php?id=<?php echo $row['inquiry_id']; ?>" class="btn-small">Reply</a>
              <a href="delete_inquiry.php?id=<?php echo $row['inquiry_id']; ?>" 
                 class="btn-small btn-danger"
                 onclick="return confirm('Are you sure you want to delete this inquiry?');">
                 Delete
              </a>
            </td>
          </tr>
        <?php } ?>
      </table>
    <?php } else { ?>
      <p>No inquiries found.</p>
    <?php } ?>
  </div>
</body>
</html>
