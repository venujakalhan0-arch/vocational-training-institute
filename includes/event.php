<?php
include('../includes/connect.php');

// Fetch all events (latest first)
$query = "SELECT * FROM events ORDER BY event_date DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Events</title>

    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="../style1.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" 
          integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" 
          crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Event Page Specific CSS -->
    <link rel="stylesheet" href="../css/event.css">
</head>
<body>

<div class="main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="icon">
            <a href="../index.php"><h2 class="logo">Skill<span>Pro.</span></h2></a>
        </div>

        <div class="menu">
            <ul>
                <li><a href="../index.php">HOME</a></li>
                <li><a href="./about.php">ABOUT</a></li>
                <li>
                    <a href="./courses.php">COURSES</a>
                </li>
                <li><a href="./event.php" class="active">EVENTS</a></li>
                <li><a href="./contact.php">CONTACT</a></li>
            </ul>
        </div>

       <div class="search">
    <a href="./includes/search_course.php">
        <button class="btn">Search Courses</button>
    </a>
</div>
    </div>

    <!-- Event Cards -->
    <div class="card_container1">
        <div class="title_courses">Upcoming & Recent Events</div>

        <?php if ($result && $result->num_rows > 0) { ?>
            <?php while ($row = $result->fetch_assoc()) { 
                $imagePath = !empty($row['image_event']) ? "../" . htmlspecialchars($row['image_event']) : "../includes/uploads/default_event.png";
            ?>
                <div class="card1">
                    <img src="<?php echo $imagePath; ?>" 
                         alt="<?php echo htmlspecialchars($row['event_name']); ?>">

                    <div class="card_content1">
                        <h3><?php echo htmlspecialchars($row['event_name']); ?></h3>

                        <p>
                            <?php 
                            $desc = htmlspecialchars($row['event_des']);
                            echo strlen($desc) > 120 ? substr($desc, 0, 120) . "..." : $desc; 
                            ?>
                        </p>

                        <p><i class="fa fa-calendar"></i> <?php echo htmlspecialchars($row['event_date']); ?></p>
                        <p><i class="fa fa-clock"></i> <?php echo htmlspecialchars($row['event_time']); ?></p>
                        <br>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p style="text-align:center;">No events available at the moment.</p>
        <?php } ?>
    </div>
</div>

</body>
</html>
