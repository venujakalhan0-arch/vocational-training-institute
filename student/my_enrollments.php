<?php
session_start();
include('../includes/connect.php');

// Only for logged-in students
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Student') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch enrolled courses (added course_id to fix error)
$query = "SELECT e.enrolled_at, c.course_id, c.course_name, c.category, 
                 c.duration, c.start_date, c.fee, c.mode, c.location, c.image
          FROM enrollments e
          JOIN courses c ON e.course_id = c.course_id
          WHERE e.user_id = ?
          ORDER BY e.enrolled_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Enrollments</title>
  <link rel="stylesheet" href="../css/my_enrollments.css">
</head>
<body>
  <div class="enrollment-container">
    <h2>My Enrolled Courses</h2>

    <?php if ($result->num_rows > 0) { ?>
      <div class="enrollment-grid">
        <?php while ($row = $result->fetch_assoc()) { ?>
          <div class="enrollment-card">
            <?php 
              $imgPath = !empty($row['image']) ? "../" . htmlspecialchars($row['image']) : "../includes/uploads/courses/default.png";
            ?>
            <img src="<?php echo $imgPath; ?>" alt="<?php echo htmlspecialchars($row['course_name']); ?>">
            
            <div class="enrollment-info">
              <h3><?php echo htmlspecialchars($row['course_name']); ?></h3>
              <p><strong>Category:</strong> <?php echo htmlspecialchars($row['category']); ?></p>
              <p><strong>Duration:</strong> <?php echo htmlspecialchars($row['duration']); ?> Months</p>
              <p><strong>Mode:</strong> <?php echo htmlspecialchars($row['mode']); ?></p>
              <p><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
              <p><strong>Start Date:</strong> <?php echo htmlspecialchars($row['start_date']); ?></p>
              <p><strong>Fee:</strong> Rs. <?php echo number_format($row['fee'], 2); ?></p>
              <small>Enrolled on: <?php echo htmlspecialchars($row['enrolled_at']); ?></small>
              <br>
              <a href="cancel_enrollment.php?course_id=<?php echo $row['course_id']; ?>" 
                 class="btn-cancel" 
                 onclick="return confirm('Are you sure you want to cancel this enrollment?');">
                 Cancel Enrollment
              </a>
            </div>
          </div>
        <?php } ?>
      </div>
    <?php } else { ?>
      <p style="text-align:center;">You haven’t enrolled in any courses yet.</p>
    <?php } ?>

    <a href="student_dashboard.php" class="btn-back">Back to Dashboard</a>
  </div>
</body>
</html>
