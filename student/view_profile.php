<?php
session_start();
include('../includes/connect.php');

// Restrict to logged-in students only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Student') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Helper: resolve image path for display
function resolveImageUrl($dbPath) {
    $defaultUrl = '../includes/uploads/default.png';
    if (empty($dbPath)) return $defaultUrl;
    $dbPathClean = preg_replace('#^(\./|/)+#', '', $dbPath);
    return "../" . $dbPathClean;
}

// Fetch student details
$stmt = $conn->prepare("SELECT username, email, phone_num, gender, image_user FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();

$resolvedImg = resolveImageUrl($user['image_user'] ?? '');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile</title>
  <link rel="stylesheet" href="../css/view_profile.css">
</head>
<body>
  <div class="profile-container">
    <div class="profile-card">
      <img src="<?php echo htmlspecialchars($resolvedImg); ?>" alt="Profile Picture" class="profile-pic">
      <h2><?php echo htmlspecialchars($user['username']); ?></h2>

      <div class="profile-info">
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone_num']); ?></p>
        <p><strong>Gender:</strong> <?php echo htmlspecialchars($user['gender']); ?></p>
      </div>

      <div class="profile-buttons">
        <a href="student_dashboard.php" class="btn-back">← Back to Dashboard</a>
      </div>
    </div>
  </div>
</body>
</html>
