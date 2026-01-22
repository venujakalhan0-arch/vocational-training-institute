<?php
session_start();
include('../includes/connect.php');

// Check only Admins can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['add_course'])) {
    $course_name = trim($_POST['course_name']);
    $category = trim($_POST['category']);
    $description = trim($_POST['course_des']);
    $instructor_id = $_POST['instructor_id'];
    $duration = $_POST['duration'];
    $start_date = $_POST['start_date'];
    $fee = $_POST['fee'];
    $mode = $_POST['mode'];
    $location = trim($_POST['location']);

    // Handle image upload
$image_path = null;
if (!empty($_FILES['image']['name'])) {
    $upload_dir = "../includes/uploads/courses/"; // ✅ public folder for course images
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    $newName = time() . "_" . basename($_FILES['image']['name']);
    $target_file = $upload_dir . $newName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        // ✅ Save relative path (from root, not from admin/)
        $image_path = "includes/uploads/courses/" . $newName;
    }
}


    $stmt = $conn->prepare("INSERT INTO courses (course_name, category, course_des, user_id, duration, start_date, image, fee, mode, location) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssisssdss", $course_name, $category, $description, $instructor_id, $duration, $start_date, $image_path, $fee, $mode, $location);

    if ($stmt->execute()) {
        echo "<script>alert('Course added successfully!'); window.location='./manage_course.php';</script>";
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
  <title>Add Course</title>
  <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
  <div class="admin-container">
    <h2>Add New Course</h2>
    <form action="add_course.php" method="POST" enctype="multipart/form-data">
      <label>Course Name</label>
      <input type="text" name="course_name" required>

      <label>Category</label>
      <input type="text" name="category" required>

      <label>Description</label>
      <textarea name="course_des" rows="4" required></textarea>

      <label>Instructor</label>
      <select name="instructor_id" required>
        <option value="">Select Instructor</option>
        <?php
        $result = $conn->query("SELECT user_id, username FROM users WHERE role='Instructor'");
        while ($row = $result->fetch_assoc()) {
            echo "<option value='{$row['user_id']}'>{$row['username']}</option>";
        }
        ?>
      </select>

      <label>Duration (Weeks)</label>
      <input type="number" name="duration" min="1" required>

      <label>Start Date</label>
      <input type="date" name="start_date" required>

      <label>Course Fee (Rs.)</label>
      <input type="number" name="fee" step="0.01" required>

      <label>Mode</label>
      <select name="mode" required>
        <option value="Online">Online</option>
        <option value="On-site">On-site</option>
      </select>

      <label>Location</label>
      <input type="text" name="location" required>

      <label>Course Image</label>
      <input type="file" name="image" accept="image/*" required>

      <button type="submit" name="add_course" class="btn">Add Course</button>
    </form>
  </div>
</body>
</html>
