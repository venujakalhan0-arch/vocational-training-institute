<?php
session_start();
include('../includes/connect.php');

// Only instructors
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Instructor') {
    header("Location: ../login.php");
    exit();
}

$instructor_id = $_SESSION['user_id'];

// Handle new notice submission
if (isset($_POST['add_notice'])) {
    $course_id = $_POST['course_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $notice_type = $_POST['notice_type'];
    $date = $_POST['date'];

    $stmt = $conn->prepare("INSERT INTO notices (course_id, title, description, notice_type, date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $course_id, $title, $description, $notice_type, $date);
    $stmt->execute();
    $stmt->close();
    $msg = "Notice added successfully!";
}

// Handle delete
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $conn->query("DELETE FROM notices WHERE id=$delete_id");
    $msg = "Notice deleted successfully!";
}

// Fetch instructor’s courses
$courses = $conn->query("SELECT * FROM courses WHERE user_id = $instructor_id");

// Fetch all notices related to instructor’s courses
$notices = $conn->query("
    SELECT n.*, c.course_name
    FROM notices n
    JOIN courses c ON n.course_id = c.course_id
    WHERE c.user_id = $instructor_id
    ORDER BY n.date ASC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Exam & Assignment Schedules</title>
  <link rel="stylesheet" href="../css/instructor_notices.css">
</head>
<body>
  <div class="container">
    <header class="header">
      <h2>🗓️ Manage Exam & Assignment Schedules</h2>
      <a href="instructor_dashboard.php" class="btn-back">← Back to Dashboard</a>
    </header>

    <?php if (!empty($msg)): ?>
      <p class="message"><?php echo htmlspecialchars($msg); ?></p>
    <?php endif; ?>

    <section class="add-section">
      <h3>Add New Schedule</h3>
      <form method="POST">
        <label>Course:</label>
        <select name="course_id" required>
          <option value="">-- Select Course --</option>
          <?php while ($row = $courses->fetch_assoc()): ?>
            <option value="<?php echo $row['course_id']; ?>"><?php echo htmlspecialchars($row['course_name']); ?></option>
          <?php endwhile; ?>
        </select>

        <label>Title:</label>
        <input type="text" name="title" placeholder="Exam / Assignment title" required>

        <label>Type:</label>
        <select name="notice_type" required>
          <option value="Exam">Exam</option>
          <option value="Assignment">Assignment</option>
        </select>

        <label>Date:</label>
        <input type="date" name="date" required>

        <label>Description:</label>
        <textarea name="description" rows="3" placeholder="Details about the exam or assignment"></textarea>

        <button type="submit" name="add_notice" class="btn-add">Add Schedule</button>
      </form>
    </section>

    <section class="view-section">
      <h3>My Schedules</h3>
      <table>
        <tr>
          <th>Course</th>
          <th>Title</th>
          <th>Type</th>
          <th>Date</th>
          <th>Description</th>
          <th>Action</th>
        </tr>
        <?php if ($notices->num_rows > 0): ?>
          <?php while ($n = $notices->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($n['course_name']); ?></td>
              <td><?php echo htmlspecialchars($n['title']); ?></td>
              <td>
                <span class="tag <?php echo strtolower($n['notice_type']); ?>">
                  <?php echo htmlspecialchars($n['notice_type']); ?>
                </span>
              </td>
              <td><?php echo htmlspecialchars($n['date']); ?></td>
              <td><?php echo nl2br(htmlspecialchars($n['description'])); ?></td>
              <td>
                <a href="?delete_id=<?php echo $n['id']; ?>" class="btn-delete" onclick="return confirm('Delete this schedule?')">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="6">No schedules added yet.</td></tr>
        <?php endif; ?>
      </table>
    </section>
  </div>
</body>
</html>
