<?php
session_start();
include('../includes/connect.php');

// Only students
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Student') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Helper: resolve DB path to a usable web URL
function resolveImageUrl($dbPath) {
    $defaultUrl = '../includes/uploads/default.png';
    if (empty($dbPath)) return $defaultUrl;
    $dbPathClean = preg_replace('#^(\./|/)+#', '', $dbPath);
    $candidates = [
        __DIR__ . '/../' . $dbPathClean,
        __DIR__ . '/' . $dbPathClean,
        (isset($_SERVER['DOCUMENT_ROOT']) ? rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/' . $dbPathClean : null),
        __DIR__ . '/../includes/uploads/' . basename($dbPathClean),
        __DIR__ . '/../uploads/' . basename($dbPathClean),
    ];
    foreach ($candidates as $fsPath) {
        if (!$fsPath) continue;
        if (file_exists($fsPath) && is_file($fsPath)) {
            $real = realpath($fsPath);
            $docroot = isset($_SERVER['DOCUMENT_ROOT']) ? realpath($_SERVER['DOCUMENT_ROOT']) : false;
            if ($docroot && strpos($real, $docroot) === 0) {
                $urlPath = str_replace('\\','/', substr($real, strlen($docroot)) );
                if ($urlPath === '' || $urlPath[0] !== '/') $urlPath = '/' . $urlPath;
                return $urlPath;
            } else {
                return '../' . $dbPathClean;
            }
        }
    }
    return $defaultUrl;
}

// Fetch user
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
  <title>Student Dashboard</title>
  <link rel="stylesheet" href="../css/student_dashboard.css">
</head>
<body>
  <div class="dashboard-container">
    <header class="dashboard-header">
      <div class="profile">
        <img src="<?php echo htmlspecialchars($resolvedImg); ?>" 
             alt="Profile Picture" class="profile-pic" 
             style="width:60px;height:60px;object-fit:cover;border-radius:50%;">
        <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
        <div class="profile-actions">
          <a href="../student/view_profile.php" class="btn-view-profile">View Profile</a>
          <a href="../admin/logout.php" class="btn-logout">Logout</a>
        </div>
      </div>
    </header>

    <!-- Dashboard Cards -->
    <main class="dashboard-main">

      <div class="card">
        <h3>📚 Available Courses</h3>
        <p>Explore available training programs and enroll.</p>
        <a href="../includes/courses.php" class="btn">View Courses</a>
      </div>

      <div class="card">
        <h3>📝 My Enrollments</h3>
        <p>Check your registered courses and schedules.</p>
        <a href="my_enrollments.php" class="btn">Go</a>
      </div>

      <!-- ✅ NEW CARD: Course Materials -->
      <div class="card">
        <h3>📘 Course Materials</h3>
        <p>View and download materials shared by your instructors.</p>
        <a href="view_materials.php" class="btn">View Materials</a>
      </div>
      <!-- ✅ END NEW CARD -->

      <div class="card">
        <h3>📢 Exams & Assignment Schedules</h3>
        <p>Stay updated with exams and assignment schedules.</p>
        <a href="./notices.php" class="btn">Go</a>
      </div>

      <div class="card">
        <h3>❓ Submit Inquiry</h3>
        <p>Have questions? Contact the admin team.</p>
        <a href="inquiry.php" class="btn">Go</a>
      </div>

    </main>
  </div>
</body>
</html>
