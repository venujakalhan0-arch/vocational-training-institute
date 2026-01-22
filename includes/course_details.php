<?php
session_start();
include('../includes/connect.php');

// Validate course ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Course ID.");
}
$course_id = (int)$_GET['id'];

// Fetch course details
$stmt = $conn->prepare("SELECT c.*, u.username AS instructor 
                        FROM courses c 
                        LEFT JOIN users u ON c.user_id = u.user_id
                        WHERE c.course_id = ?");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$course = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$course) {
    die("Course not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($course['course_name']); ?> - Course Details</title>
  <link rel="stylesheet" href="../style1.css">
  <link rel="stylesheet" href="../css/course_details.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
</head>
<body>

<div class="main">
  <!-- Navbar -->
  <div class="navbar">
      <div class="icon">
          <a href="../index.php"><h2 class="logo">Skill<span>Pro.</span></h2></a>
      </div>

      <div class="menu">
          <ul>
              <li><a href="../index.php">HOME</a></li>
              <li><a href="#">ABOUT</a></li>
              <li><a href="#">COURSES</a></li>
              <li><a href="../includes/event.php">EVENTS</a></li>
              <li><a href="#">CONTACT</a></li>
          </ul>
      </div>

      <div class="search">
          <input class="srch" type="search" placeholder="Type To Search">
          <a href="#"><button class="btn">Search</button></a>
      </div>
  </div>

  <!-- Course Details Section -->
  <div class="course-details-container">
    <div class="course-header">
      <img src="../<?php echo htmlspecialchars($course['image']); ?>" alt="<?php echo htmlspecialchars($course['course_name']); ?>" class="course-image">
      <div class="course-info">
        <h1><?php echo htmlspecialchars($course['course_name']); ?></h1>
        <p><strong>Category:</strong> <?php echo htmlspecialchars($course['category']); ?></p>
        <p><strong>Instructor:</strong> <?php echo htmlspecialchars($course['instructor'] ?? 'Not Assigned'); ?></p>
        <p><strong>Duration:</strong> <?php echo htmlspecialchars($course['duration']); ?> Months</p>
        <p><strong>Fee:</strong> Rs. <?php echo number_format($course['fee'], 2); ?></p>
        <p><strong>Mode:</strong> <?php echo htmlspecialchars($course['mode']); ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($course['location']); ?></p>
        <p><strong>Start Date:</strong> <?php echo htmlspecialchars($course['start_date']); ?></p>
      </div>
    </div>

    <div class="course-description">
      <h2>Course Description</h2>
      <p><?php echo nl2br(htmlspecialchars($course['course_des'])); ?></p>
    </div>

    <div class="enroll-section">
      <?php
      if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'Student') {
          echo '<a href="../student/enroll.php?course_id=' . $course['course_id'] . '" class="btn_enroll">Enroll Now</a>';
      } else {
          echo '<a href="./login.php?redirect=../includes/course_details.php?id=' . $course['course_id'] . '" class="btn_enroll">Login to Enroll</a>';
      }
      ?>
    </div>
  </div>

</div>

</body>
</html>
