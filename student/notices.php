<?php
session_start();
include('../includes/connect.php');

// Only allow students
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Student') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch notices (exam & assignment) for enrolled courses
$query = "
    SELECT n.*, c.course_name 
    FROM notices n
    JOIN courses c ON n.course_id = c.course_id
    WHERE n.course_id IN (
        SELECT course_id FROM enrollments WHERE user_id = $user_id
    )
";
$result = $conn->query($query);

// Prepare events for FullCalendar
$events = [];
while ($row = $result->fetch_assoc()) {
    $color = ($row['notice_type'] === 'Exam') ? '#007bff' : '#f59e0b';
    $events[] = [
        'title' => $row['course_name'] . ' - ' . $row['title'] . ' (' . $row['notice_type'] . ')',
        'start' => $row['date'],
        'description' => $row['description'],
        'color' => $color
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Exam & Assignment Schedule</title>

  <!-- ✅ Correct FullCalendar Global Build -->
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

  <style>
    body {
      font-family: "Poppins", sans-serif;
      background: #f8f9fa;
      margin: 0;
      padding: 0;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #20c997;
      color: #fff;
      padding: 15px 25px;
      border-radius: 10px;
      width: 90%;
      margin: 30px auto 0;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
    }

    .btn-back {
      background: #fff;
      color: #20c997;
      padding: 8px 14px;
      border-radius: 5px;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .btn-back:hover {
      background: #008080;
      color: #fff;
    }

    #calendar {
      max-width: 90%;
      margin: 40px auto;
      background: #fff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }

    .fc-toolbar-title {
      color: #008080;
      font-weight: 600;
    }

    .fc-event {
      border-radius: 6px;
      padding: 2px;
      font-size: 0.9rem;
    }

    .no-events {
      text-align: center;
      font-size: 1.1rem;
      color: #555;
      margin-top: 60px;
    }

    /* FullCalendar button styling */
    .fc .fc-button {
      background: #20c997;
      border: none;
      color: #fff;
      border-radius: 5px;
      padding: 6px 10px;
      transition: background 0.3s ease;
    }

    .fc .fc-button:hover {
      background: #128f76;
    }

    .fc .fc-button-primary:disabled {
      background: #aaa;
    }

    .fc {
      font-family: "Poppins", sans-serif;
    }
  </style>
</head>
<body>
  <header class="header">
    <h2>📅 Exam & Assignment Calendar</h2>
    <a href="student_dashboard.php" class="btn-back">← Back to Dashboard</a>
  </header>

  <div id="calendar"></div>

  <?php if (empty($events)) : ?>
    <p class="no-events">No exam or assignment schedules have been posted yet.</p>
  <?php endif; ?>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var events = <?php echo json_encode($events); ?>;
      if (events.length > 0) {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          height: 'auto',
          events: events,
          eventClick: function(info) {
            alert(
              "📘 " + info.event.title + "\n\n" +
              (info.event.extendedProps.description || "No description provided.")
            );
          },
          headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listWeek'
          }
        });
        calendar.render();
      }
    });
  </script>
</body>
</html>
