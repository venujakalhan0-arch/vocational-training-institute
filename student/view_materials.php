<?php
session_start();
include('../includes/connect.php');

// Only students
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Student') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get student’s enrolled courses
$courses = $conn->query("
  SELECT c.course_id, c.course_name
  FROM enrollments e
  JOIN courses c ON e.course_id = c.course_id
  WHERE e.user_id = $user_id
");

// Fetch materials for these courses
$materials = $conn->query("
  SELECT m.*, c.course_name, u.username AS instructor_name
  FROM materials m
  JOIN courses c ON m.course_id = c.course_id
  JOIN users u ON m.instructor_id = u.user_id
  WHERE m.course_id IN (SELECT course_id FROM enrollments WHERE user_id = $user_id)
  ORDER BY m.upload_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Materials</title>
  <link rel="stylesheet" href="../css/student_materials.css">
</head>
<body>
  <div class="container">
    <header class="header">
      <h2>📘 Course Materials</h2>
      <a href="student_dashboard.php" class="btn-back">← Back to Dashboard</a>
    </header>

    <section class="materials-section">
      <table>
        <tr>
          <th>Course</th>
          <th>Material Title</th>
          <th>Instructor</th>
          <th>Uploaded Date</th>
          <th>Download</th>
        </tr>
        <?php if ($materials->num_rows > 0): ?>
          <?php while ($m = $materials->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($m['course_name']); ?></td>
              <td><?php echo htmlspecialchars($m['title']); ?></td>
              <td><?php echo htmlspecialchars($m['instructor_name']); ?></td>
              <td><?php echo htmlspecialchars($m['upload_date']); ?></td>
              <td><a href="<?php echo $m['file_path']; ?>" download class="btn-download">Download</a></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="5">No materials available.</td></tr>
        <?php endif; ?>
      </table>
    </section>
  </div>
</body>
</html>
