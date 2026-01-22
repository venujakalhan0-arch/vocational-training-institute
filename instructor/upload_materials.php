<?php
session_start();
include('../includes/connect.php');

// Only instructors can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Instructor') {
    header("Location: ../login.php");
    exit();
}

$instructor_id = $_SESSION['user_id'];

// Handle file upload
if (isset($_POST['upload'])) {
    $course_id = $_POST['course_id'];
    $title = $_POST['title'];
    $file = $_FILES['material_file'];

    if (!empty($file['name'])) {
        $target_dir = "../uploads/materials/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);

        $filename = time() . "_" . basename($file["name"]);
        $target_file = $target_dir . $filename;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed = ['pdf', 'ppt', 'pptx', 'doc', 'docx', 'xls', 'xlsx', 'mp4', 'jpg', 'png'];
        if (in_array($file_type, $allowed)) {
            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                $stmt = $conn->prepare("INSERT INTO materials (course_id, instructor_id, title, file_path, upload_date) VALUES (?, ?, ?, ?, NOW())");
                $stmt->bind_param("iiss", $course_id, $instructor_id, $title, $target_file);
                $stmt->execute();
                $msg = "Material uploaded successfully!";
            } else {
                $msg = "Error uploading file.";
            }
        } else {
            $msg = "Invalid file type.";
        }
    } else {
        $msg = "Please choose a file.";
    }
}

// Delete file
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $res = $conn->query("SELECT file_path FROM materials WHERE id=$delete_id");
    $file = $res->fetch_assoc();
    if ($file && file_exists($file['file_path'])) unlink($file['file_path']);
    $conn->query("DELETE FROM materials WHERE id=$delete_id");
    $msg = "Material deleted successfully!";
}

// Fetch instructor’s courses
$courses = $conn->query("SELECT * FROM courses WHERE user_id = $instructor_id");

// Fetch uploaded materials
$materials = $conn->query("
    SELECT m.*, c.course_name 
    FROM materials m 
    JOIN courses c ON m.course_id = c.course_id 
    WHERE m.instructor_id = $instructor_id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Upload Materials</title>
  <link rel="stylesheet" href="../css/instructor_materials.css">
</head>
<body>
  <div class="container">
    <header class="header">
      <h2>📂 Upload Course Materials</h2>
      <a href="instructor_dashboard.php" class="btn-back">← Back to Dashboard</a>
    </header>

    <?php if (!empty($msg)): ?>
      <p class="message"><?php echo htmlspecialchars($msg); ?></p>
    <?php endif; ?>

    <section class="upload-section">
      <form method="POST" enctype="multipart/form-data">
        <label for="course_id">Select Course:</label>
        <select name="course_id" required>
          <option value="">-- Select --</option>
          <?php while ($row = $courses->fetch_assoc()): ?>
            <option value="<?php echo $row['course_id']; ?>"><?php echo htmlspecialchars($row['course_name']); ?></option>
          <?php endwhile; ?>
        </select>

        <label for="title">Material Title:</label>
        <input type="text" name="title" placeholder="Enter title..." required>

        <label for="file">Upload File:</label>
        <input type="file" name="material_file" required>

        <button type="submit" name="upload" class="btn-upload">Upload</button>
      </form>
    </section>

    <section class="uploaded-section">
      <h3>Uploaded Materials</h3>
      <table>
        <tr>
          <th>Course</th>
          <th>Title</th>
          <th>File</th>
          <th>Date</th>
          <th>Action</th>
        </tr>
        <?php while ($row = $materials->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['course_name']); ?></td>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td><a href="<?php echo $row['file_path']; ?>" target="_blank">View</a></td>
            <td><?php echo htmlspecialchars($row['upload_date']); ?></td>
            <td><a href="?delete_id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Delete this material?')">Delete</a></td>
          </tr>
        <?php endwhile; ?>
      </table>
    </section>
  </div>
</body>
</html>
