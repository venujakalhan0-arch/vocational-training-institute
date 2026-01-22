<?php
session_start();
include('../includes/connect.php');

// Only Admins allowed
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone_num']);
    $role     = trim($_POST['role']); // Student or Instructor
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Handle profile picture upload
    $profilePic = "includes/uploads/default.png";
    if (isset($_FILES['profile']) && $_FILES['profile']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "../includes/uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $fileName  = uniqid() . "_" . basename($_FILES["profile"]["name"]);
        $targetFile = $targetDir . $fileName;
        if (move_uploaded_file($_FILES["profile"]["tmp_name"], $targetFile)) {
            $profilePic = "includes/uploads/" . $fileName;
        }
    }

    // Insert user
    $stmt = $conn->prepare("INSERT INTO users (username, email, phone_num, password, role, image_user) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $username, $email, $phone, $password, $role, $profilePic);
    $stmt->execute();
    $stmt->close();

    // Redirect to appropriate management page
    if ($role === 'Instructor') {
        header("Location: manage_users.php");
    } else {
        header("Location: manage_users.php"); // You can also make manage_students.php if you want
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add User</title>
  <link rel="stylesheet" href="../css/add_user.css"> <!-- Reusing same CSS -->
</head>
<body>
  <div class="admin-container">
    <h2>Add New User</h2>
    <form method="POST" enctype="multipart/form-data">
      <label>Full Name:</label>
      <input type="text" name="username" required>

      <label>Email:</label>
      <input type="email" name="email" required>

      <label>Phone:</label>
      <input type="text" name="phone_num" required>

      <label>Password:</label>
      <input type="password" name="password" required>

      <label>Role:</label>
      <select name="role" required>
        <option value="">Select Role</option>
        <option value="Instructor">Instructor</option>
        <option value="Student">Student</option>
      </select>

      <label>Profile Picture:</label>
      <input type="file" name="profile" accept="image/*">

      <input type="submit" value="Add User">
    </form>
  </div>
</body>
</html>
