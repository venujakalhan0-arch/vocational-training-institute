<?php
session_start();
include('../includes/connect.php');

// Only Admins allowed
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

// Fetch all users except Admins (optional)
$result = $conn->query("SELECT user_id, username, email, phone_num, role, created_at, image_user 
                        FROM users 
                        WHERE role IN ('Instructor','Student') 
                        ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Users</title>
  <link rel="stylesheet" href="../css/manage_users.css">
</head>
<body>
  <div class="admin-container">
    <h2>Manage Users</h2>
    <a href="add_user.php" class="btn">+ Add New User</a>
    <a href="./admin_dashboard.php" class="btn">Back to Admin Dashboard</a>
    <table>
      <tr>
        <th>ID</th>
        <th>Profile</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Role</th>
        <th>Joined</th>
        <th>Actions</th>
      </tr>
      <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
          <td><?php echo $row['user_id']; ?></td>
          <td>
            <?php if (!empty($row['image_user'])) { ?>
              <img src="../<?php echo $row['image_user']; ?>" width="50" style="border-radius:50%;">
            <?php } else { ?>
              <img src="../includes/uploads/default.png" width="50" style="border-radius:50%;">
            <?php } ?>
          </td>
          <td><?php echo htmlspecialchars($row['username']); ?></td>
          <td><?php echo htmlspecialchars($row['email']); ?></td>
          <td><?php echo htmlspecialchars($row['phone_num']); ?></td>
          <td><?php echo htmlspecialchars($row['role']); ?></td>
          <td><?php echo htmlspecialchars($row['created_at']); ?></td>
          <td>
            <a href="edit_user.php?id=<?php echo $row['user_id']; ?>" class="btn-small">Edit</a>
            <a href="delete_user.php?id=<?php echo $row['user_id']; ?>" 
               class="btn-small btn-danger"
               onclick="return confirm('Are you sure you want to delete this user?')">
               Delete
            </a>
          </td>
        </tr>
      <?php } ?>
    </table>
  </div>
</body>
</html>
