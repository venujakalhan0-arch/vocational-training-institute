<?php
session_start();
include('../includes/connect.php');

// Only Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    exit("Access denied");
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    exit("Invalid user ID.");
}

$user_id = intval($_GET['id']);

// Fetch user
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id=? AND role IN ('Instructor','Student')");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    exit("User not found.");
}
$user = $result->fetch_assoc();
$stmt->close();

// Handle form submit
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $phone    = $_POST['phone_num'];
    $role     = $_POST['role'];
    $gender   = $_POST['gender'];
    $new_password = $_POST['new_password'];

    // Profile pic update
    $profilePic = $user['image_user'];
    if (isset($_FILES['profile']) && $_FILES['profile']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "../includes/uploads/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $fileName = uniqid() . "_" . basename($_FILES["profile"]["name"]);
        $targetFile = $targetDir . $fileName;
        $allowedTypes = ['image/jpeg','image/png','image/gif'];
        if (in_array($_FILES['profile']['type'], $allowedTypes)) {
            if (move_uploaded_file($_FILES["profile"]["tmp_name"], $targetFile)) {
                $profilePic = "includes/uploads/" . $fileName;
            }
        }
    }

    // Update base details
    $stmt = $conn->prepare("UPDATE users SET username=?, email=?, phone_num=?, role=?, gender=?, image_user=? WHERE user_id=?");
    $stmt->bind_param("ssssssi", $username, $email, $phone, $role, $gender, $profilePic, $user_id);
    $stmt->execute();
    $stmt->close();

    // Update password if given
    if (!empty($new_password)) {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password=? WHERE user_id=?");
        $stmt->bind_param("si", $hashed, $user_id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: manage_users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit User</title>
  <link rel="stylesheet" href="../css/edit_user.css">
</head>
<body>
  <div class="admin-container">
    <h2>Edit User</h2>
    <form method="POST" enctype="multipart/form-data">
      <label>Full Name</label>
      <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

      <label>Email</label>
      <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

      <label>Phone</label>
      <input type="text" name="phone_num" value="<?= htmlspecialchars($user['phone_num']) ?>" required>

      <label>Role</label>
      <select name="role" required>
        <option value="Student" <?= $user['role']=="Student"?"selected":"" ?>>Student</option>
        <option value="Instructor" <?= $user['role']=="Instructor"?"selected":"" ?>>Instructor</option>
      </select>

      <label>Gender</label>
      <select name="gender" required>
        <option value="Male" <?= $user['gender']=="Male"?"selected":"" ?>>Male</option>
        <option value="Female" <?= $user['gender']=="Female"?"selected":"" ?>>Female</option>
        <option value="Prefer not to say" <?= $user['gender']=="Prefer not to say"?"selected":"" ?>>Prefer not to say</option>
      </select>

      <label>Current Profile Picture</label><br>
      <?php if (!empty($user['image_user'])): ?>
        <img src="../<?= htmlspecialchars($user['image_user']) ?>" width="100"><br>
      <?php else: ?>
        <img src="../includes/uploads/default.png" width="100"><br>
      <?php endif; ?>
      <input type="file" name="profile" accept="image/*">

      <label>Reset Password</label>
      <input type="password" name="new_password" placeholder="Leave blank to keep current password">

      <input type="submit" value="Update User">
    </form>
    <p><a href="manage_users.php" class="back-link">← Back to Users</a></p>
  </div>
</body>
</html>
