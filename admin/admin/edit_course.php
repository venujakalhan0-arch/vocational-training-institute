<?php
session_start();
include('../includes/connect.php');

// ✅ Only Admins allowed
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

// ✅ Validate course ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Course ID.");
}
$course_id = (int)$_GET['id'];

// ✅ Fetch existing course info
$stmt = $conn->prepare("SELECT * FROM courses WHERE course_id = ?");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$course = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$course) {
    die("Course not found.");
}

// ✅ Fetch instructors for dropdown
$instructors = $conn->query("SELECT user_id, username FROM users WHERE role='Instructor'");

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_name  = $_POST['course_name'];
    $category     = $_POST['category'];
    $duration     = $_POST['duration'];
    $fee          = $_POST['fee'];
    $mode         = $_POST['mode'];
    $location     = $_POST['location'];
    $start_date   = $_POST['start_date'];
    $user_id      = $_POST['user_id'] ?: null;
    $course_des   = $_POST['course_des']; // ✅ Description field

    // ✅ Handle image upload
    $image = $course['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "../includes/uploads/courses/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $fileName = uniqid() . "_" . basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $fileName;

        $allowed = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['image']['type'], $allowed)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                $image = "includes/uploads/courses/" . $fileName;
            }
        }
    }

    // ✅ Update database — fixed bind_param types (11 vars total)
    $stmt = $conn->prepare("UPDATE courses 
                            SET course_name=?, category=?, duration=?, fee=?, mode=?, location=?, start_date=?, user_id=?, image=?, course_des=? 
                            WHERE course_id=?");

    // Type definitions:
    // s = string, i = integer, d = double (for fee)
    // course_name(s), category(s), duration(s), fee(d), mode(s), location(s), start_date(s), user_id(i), image(s), course_des(s), course_id(i)
    $stmt->bind_param("sssdsssissi", 
        $course_name, $category, $duration, $fee, $mode, $location, 
        $start_date, $user_id, $image, $course_des, $course_id
    );

    if ($stmt->execute()) {
        echo "<script>alert('✅ Course updated successfully!'); window.location='manage_course.php';</script>";
    } else {
        echo "<script>alert('❌ Error updating course.');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Course</title>
  <link rel="stylesheet" href="../css/edit_course.css">
</head>
<body>
  <div class="admin-container">
    <h2>Edit Course</h2>

    <form method="POST" enctype="multipart/form-data">
      <label>Course Name:</label>
      <input type="text" name="course_name" value="<?= htmlspecialchars($course['course_name']) ?>" required><br>

      <label>Category:</label>
      <input type="text" name="category" value="<?= htmlspecialchars($course['category']) ?>" required><br>

      <label>Duration (months):</label>
      <input type="number" name="duration" value="<?= htmlspecialchars($course['duration']) ?>" required><br>

      <label>Fee (Rs.):</label>
      <input type="number" step="0.01" name="fee" value="<?= htmlspecialchars($course['fee']) ?>" required><br>

      <label>Mode:</label>
      <select name="mode" required>
        <option value="Online" <?= ($course['mode'] === 'Online') ? 'selected' : '' ?>>Online</option>
        <option value="On-site" <?= ($course['mode'] === 'On-site') ? 'selected' : '' ?>>On-site</option>
      </select><br>

      <label>Location:</label>
      <input type="text" name="location" value="<?= htmlspecialchars($course['location']) ?>"><br>

      <label>Start Date:</label>
      <input type="date" name="start_date" value="<?= htmlspecialchars($course['start_date']) ?>" required><br>

      <label>Instructor:</label>
      <select name="user_id">
        <option value="">-- None --</option>
        <?php while ($ins = $instructors->fetch_assoc()) { ?>
          <option value="<?= $ins['user_id'] ?>" <?= ($ins['user_id'] == $course['user_id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($ins['username']) ?>
          </option>
        <?php } ?>
      </select><br>

      <!-- ✅ Description Field -->
      <label>Course Description:</label><br>
      <textarea name="course_des" rows="5" cols="50" required><?= htmlspecialchars($course['course_des'] ?? '') ?></textarea><br>

      <label>Current Image:</label><br>
      <?php if (!empty($course['image'])) { ?>
        <img src="../<?= htmlspecialchars($course['image']) ?>" width="100"><br>
      <?php } else { ?>
        <p>No image uploaded.</p>
      <?php } ?>

      <input type="file" name="image" accept="image/*"><br><br>

      <button type="submit" class="btn">Update Course</button>
      <a href="manage_course.php" class="btn-secondary">Cancel</a>
    </form>
  </div>
</body>
</html>
