<?php
// admin_dashboard.php
session_start();
include '../includes/connect.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../includes/login.php");
    exit;
}

// Access control: ensure this page is accessible to Admin only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    // Adjust redirect if your login page path is different
    header('Location: ../includes/login.php');
    exit;
}

// Helper: get single integer result
function get_count($conn, $sql) {
    $res = $conn->query($sql);
    if ($res && ($row = $res->fetch_row())) return (int)$row[0];
    return 0;
}

// 1) Users count (total and by role)
$total_users = get_count($conn, "SELECT COUNT(*) FROM users");
$students = get_count($conn, "SELECT COUNT(*) FROM users WHERE role='Student'");
$instructors = get_count($conn, "SELECT COUNT(*) FROM users WHERE role='Instructor'");

// 2) Courses count
$total_courses = get_count($conn, "SELECT COUNT(*) FROM courses");

// 3) Events count (upcoming events: event_date >= today)
$today = date('Y-m-d');
$upcoming_events = get_count($conn, "SELECT COUNT(*) FROM events WHERE event_date >= '{$today}'");

// 4) Inquiries count - check whether table exists and count
$inq_count = 0;
$check_inq = $conn->prepare("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = ? AND table_name = ?");
$dbname = $conn->real_escape_string($conn->query("SELECT DATABASE()")->fetch_row()[0]);
$tbl = 'inquiries';
$check_inq->bind_param("ss", $dbname, $tbl);
$check_inq->execute();
$check_inq->bind_result($has_inq);
$check_inq->fetch();
$check_inq->close();
if ($has_inq) {
    $inq_count = get_count($conn, "SELECT COUNT(*) FROM inquiries");
}

// 5) Recent users (limit 6)
$recent_users = [];
$res = $conn->query("SELECT user_id, username, email, role, created_at FROM users ORDER BY created_at DESC LIMIT 6");
if ($res) {
    while ($r = $res->fetch_assoc()) $recent_users[] = $r;
}

// 6) Recent courses (limit 6)
$recent_courses = [];
$res = $conn->query("SELECT course_id, course_name, duration, fee, mode, start_date FROM courses ORDER BY start_date DESC LIMIT 6");
if ($res) {
    while ($r = $res->fetch_assoc()) $recent_courses[] = $r;
}

// Close connection if you want (optional)
// $conn->close();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>SkillPro - Admin Dashboard</title>
  <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
  <div class="container">

    <header class="header">
      <div class="brand">
        <img src="https://placehold.co/80x80?text=SP" alt="SkillPro logo">
        <div>
          <h1>SkillPro Institute — Admin</h1>
          <div class="small">Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></div>
        </div>
      </div>
      <nav class="topnav">
        <a class="btn" href="../admin/manage_users.php">Manage Users</a>
        <a class="btn" href="./manage_course.php">Manage Courses</a>
        <a class="btn" href="./manage_events.php">Manage Events</a>
        <a class="btn btn-primary" href="manage_inquiries.php">Inquiries (<?php echo $inq_count; ?>)</a>
        <a class="btn btn-logout" href="logout.php">Logout</a>
      </nav>
    </header>

    <main>
      <section class="grid" style="margin-bottom:16px;">
        <div class="tile card">
          <div class="title">Total users</div>
          <div class="value"><?php echo $total_users; ?></div>
          <div class="small">Students: <?php echo $students; ?> · Instructors: <?php echo $instructors; ?></div>
        </div>

        <div class="tile card">
          <div class="title">Courses</div>
          <div class="value"><?php echo $total_courses; ?></div>
          <div class="small">Active/Upcoming courses</div>
        </div>

        <div class="tile card">
          <div class="title">Upcoming events</div>
          <div class="value"><?php echo $upcoming_events; ?></div>
          <div class="small">Events from <?php echo htmlspecialchars($today); ?></div>
        </div>

        <div class="tile card">
          <div class="title">Inquiries</div>
          <div class="value"><?php echo $inq_count; ?></div>
          <div class="small">From visitors or students</div>
        </div>
      </section>

      <section class="grid">
        <div class="left">
          <div class="card">
            <h3 style="margin-bottom:10px;">Recent students / users</h3>
            <?php if (count($recent_users)): ?>
            <table class="table" aria-describedby="recent-users">
              <thead>
                <tr><th>Id</th><th>Name</th><th>Email</th><th>Role</th><th>Joined</th></tr>
              </thead>
              <tbody>
                <?php foreach($recent_users as $u): ?>
                <tr>
                  <td><?php echo htmlspecialchars($u['user_id']); ?></td>
                  <td><?php echo htmlspecialchars($u['username']); ?></td>
                  <td><?php echo htmlspecialchars($u['email']); ?></td>
                  <td><?php echo htmlspecialchars($u['role']); ?></td>
                  <td><?php echo htmlspecialchars($u['created_at']); ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <?php else: ?>
              <div class="small">No users found.</div>
            <?php endif; ?>
          </div>

          <div class="card">
            <h3 style="margin-bottom:10px;">Recent courses</h3>
            <?php if (count($recent_courses)): ?>
            <table class="table" aria-describedby="recent-courses">
              <thead>
                <tr><th>Id</th><th>Course</th><th>Duration (weeks)</th><th>Fee</th><th>Start</th></tr>
              </thead>
              <tbody>
                <?php foreach($recent_courses as $c): ?>
                <tr>
                  <td><?php echo htmlspecialchars($c['course_id']); ?></td>
                  <td><?php echo htmlspecialchars($c['course_name']); ?></td>
                  <td><?php echo htmlspecialchars($c['duration']); ?></td>
                  <td><?php echo htmlspecialchars($c['fee']); ?></td>
                  <td><?php echo htmlspecialchars($c['start_date']); ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <?php else: ?>
              <div class="small">No courses found.</div>
            <?php endif; ?>
          </div>
        </div>

        <aside class="right">
          <div class="card">
            <h3>Quick actions</h3>
            <ul style="margin-top:8px;">
              <li><a href="./add_course.php">+ Add Course</a></li>
              <li><a href="../admin/add_user.php">+ Add User</a></li>
              <li><a href="../admin/add_event.php">+ Add Event</a></li>
              <li><a href="manage_inquiries.php">Manage Inquiries</a></li>
            </ul>
          </div>

          <div class="card">
            <h3>System info</h3>
            <div class="small" style="margin-top:8px;">
              PHP version: <?php echo phpversion(); ?><br>
              Database: <?php echo htmlspecialchars($dbname); ?><br>
              Today: <?php echo htmlspecialchars($today); ?>
            </div>
          </div>

        </aside>
      </section>
    </main>

  </div>
</body>
</html>
