<?php
session_start();
include('../includes/connect.php');

// Access control — only for instructors
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Instructor') {
    header("Location: ../login.php");
    exit();
}

$instructor_id = $_SESSION['user_id'];

// Validate course ID
if (!isset($_GET['course_id']) || !is_numeric($_GET['course_id'])) {
    die("Invalid Course ID");
}
$course_id = (int)$_GET['course_id'];

// ✅ Verify that the instructor owns this course
$check = $conn->prepare("SELECT course_name FROM courses WHERE course_id = ? AND user_id = ?");
$check->bind_param("ii", $course_id, $instructor_id);
$check->execute();
$check_result = $check->get_result();
$course = $check_result->fetch_assoc();
$check->close();

if (!$course) {
    die("You are not authorized to view this course or it does not exist.");
}

// ✅ Fetch enrolled students for this course
$stmt = $conn->prepare("
    SELECT u.user_id, u.username, u.email, u.phone_num, u.gender, e.enrolled_at
    FROM enrollments e
    JOIN users u ON e.user_id = u.user_id
    WHERE e.course_id = ?
    ORDER BY e.enrolled_at DESC
");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Students Enrolled - <?php echo htmlspecialchars($course['course_name']); ?></title>
    <link rel="stylesheet" href="../css/instructor.css">
</head>
<body>
<div class="container">
    <header class="header">
        <h2>Students Enrolled in <?php echo htmlspecialchars($course['course_name']); ?></h2>
        <nav class="topnav">
            <a href="instructor_dashboard.php">Back to Dashboard</a>
            <a href="../admin/logout.php" class="btn-logout">Logout</a>
        </nav>
    </header>

    <section class="card">
        <?php if ($result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>#</th>
                    <th>Student Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Gender</th>
                    <th>Enrolled Date</th>
                </tr>
                <?php 
                $count = 1;
                while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $count++; ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone_num']); ?></td>
                        <td><?php echo htmlspecialchars($row['gender']); ?></td>
                        <td><?php echo htmlspecialchars($row['enrolled_at']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No students have enrolled in this course yet.</p>
        <?php endif; ?>
    </section>
</div>
</body>
</html>
