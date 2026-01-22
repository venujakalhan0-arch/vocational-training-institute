<?php
session_start();
include('../includes/connect.php');

// Access control: Instructor only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Instructor') {
    header("Location: ../login.php");
    exit();
}

$instructor_id = $_SESSION['user_id'];

// Fetch instructor details directly from users table
$stmt = $conn->prepare("SELECT username, email, phone_num, image_user FROM users WHERE user_id=?");
$stmt->bind_param("i", $instructor_id);
$stmt->execute();
$instructor = $stmt->get_result()->fetch_assoc();
$stmt->close();


// Fetch courses assigned to this instructor
$courses = [];
$result = $conn->query("SELECT * FROM courses WHERE user_id = $instructor_id");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Instructor Dashboard</title>
  <link rel="stylesheet" href="../css/instructor.css">
</head>
<body>
  <div class="container">

    <!-- Header -->
    <header class="header">
      <h2>Welcome, <?php echo htmlspecialchars($instructor['username']); ?> (Instructor)</h2>
      <nav class="topnav">

        <a href="upload_materials.php">Upload Materials</a>
        <a href="notices.php">Manage Schedules</a>

        <a href="../admin/logout.php" class="btn-logout">Logout</a>
      </nav>
    </header>

    <!-- Profile Section -->
    <section class="card">
      <h3>My Profile</h3>
      <?php if (!empty($instructor['image_user'])) { ?>
    <img src="../<?php echo $instructor['image_user']; ?>" width="120" style="border-radius:50%; object-fit:cover;">
<?php } else { ?>
    <img src="../includes/uploads/default.png" width="120" style="border-radius:50%; object-fit:cover;">
<?php } ?>


      <p><strong>Name:</strong> <?php echo htmlspecialchars($instructor['username']); ?></p>
      <p><strong>Email:</strong> <?php echo htmlspecialchars($instructor['email']); ?></p>
      <p><strong>Phone:</strong> <?php echo htmlspecialchars($instructor['phone_num']); ?></p>
    </section>

    <!-- Courses Section -->
    <section class="card courses">
      <h3>My Courses</h3>
      <?php if (count($courses) > 0): ?>
        <table>
          <tr>
            <th>ID</th>
            <th>Course</th>
            <th>Category</th>
            <th>Duration</th>
            <th>Mode</th>
            <th>Start</th>
            <th>Actions</th>
          </tr>
          <?php foreach ($courses as $c): ?>
            <tr>
              <td><?php echo $c['course_id']; ?></td>
              <td><?php echo htmlspecialchars($c['course_name']); ?></td>
              <td><?php echo htmlspecialchars($c['category']); ?></td>
              <td><?php echo htmlspecialchars($c['duration']); ?> Months</td>
              <td><?php echo htmlspecialchars($c['mode']); ?></td>
              <td><?php echo htmlspecialchars($c['start_date']); ?></td>
              <td>
                <a href="view_students.php?course_id=<?php echo $c['course_id']; ?>" class="btn-small">View Students</a>
                <a href="upload_materials.php?course_id=<?php echo $c['course_id']; ?>" class="btn-small">Upload Materials</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </table>
      <?php else: ?>
        <p>No courses assigned yet.</p>
      <?php endif; ?>
    </section>

  </div>
</body>
</html>
