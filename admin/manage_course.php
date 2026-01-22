<?php
session_start();
include('../includes/connect.php');

// Only Admins allowed
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

// Fetch all courses with instructor names
$result = $conn->query("SELECT c.*, u.username AS instructor 
                        FROM courses c 
                        LEFT JOIN users u ON c.user_id = u.user_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Courses</title>
  <link rel="stylesheet" href="../css/manage_course.css"> <!-- External CSS -->
</head>
<body>
  <div class="admin-container">
    <h2>Manage Courses</h2>
    <a href="add_course.php" class="btn">+ Add New Course</a>
    <a href="./admin_dashboard.php" class="btn">Back to Admin Dashboard</a>
    <table>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Category</th>
        <th>Instructor</th>
        <th>Duration</th>
        <th>Start Date</th>
        <th>Fee (Rs.)</th>
        <th>Mode</th>
        <th>Location</th>
        <th>Image</th>
        <th>Actions</th>
      </tr>
      <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
          <td><?php echo $row['course_id']; ?></td>
          <td><?php echo htmlspecialchars($row['course_name']); ?></td>
          <td><?php echo htmlspecialchars($row['category']); ?></td>
          <td><?php echo $row['instructor'] ?: 'Not Assigned'; ?></td>
          <td><?php echo $row['duration']; ?> Months</td>
          <td><?php echo $row['start_date']; ?></td>
          <td><?php echo number_format($row['fee'], 2); ?></td>
          <td><?php echo $row['mode']; ?></td>
          <td><?php echo htmlspecialchars($row['location']); ?></td>
          <td>
  <?php if (!empty($row['image'])) { ?>
    <img src="../<?php echo htmlspecialchars($row['image']); ?>" width="70">
  <?php } else { echo "No Image"; } ?>
</td>

          <td>
            <a href="edit_course.php?id=<?php echo $row['course_id']; ?>" class="btn-small">Edit</a>
            <a href="delete_course.php?id=<?php echo $row['course_id']; ?>" 
               class="btn-small btn-danger"
               onclick="return confirm('Are you sure you want to delete this course?')">
               Delete
            </a>
          </td>
        </tr>
      <?php } ?>
    </table>
  </div>
</body>
</html>