<?php
include('../includes/connect.php');

// Fetch all courses (now includes description)
$query = "SELECT * FROM courses ORDER BY created_at ASC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Courses</title>

    <!-- Link stylesheet -->
    <link rel="stylesheet" href="../style1.css">

    <!-- Font Awesome icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" 
          integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" 
          crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

<div class="main">
    <div class="navbar">
        <!-- Logo -->
        <div class="icon">
            <a href="../index.php"><h2 class="logo">Skill<span>Pro.</span></h2></a>
        </div>

        <!-- Navigation -->
        <div class="menu">
            <ul>
                <li><a href="../index.php">HOME</a></li>
                <li><a href="./about.php">ABOUT</a></li>
                <li>
                    <a href="./courses.php">COURSES</a>
                </li>
                <li><a href="./event.php">EVENTS</a></li>
                <li><a href="./contact.php">CONTACT</a></li>
            </ul>
        </div>

        <!-- Search bar -->
        <div class="search">
    <a href="./includes/search_course.php">
        <button class="btn">Search Courses</button>
    </a>
</div>
    </div>

    <!-- Course Cards -->
    <div class="card_container1">
        <div class="title_courses">Main Courses</div>

        <?php if ($result && $result->num_rows > 0) { ?>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <div class="card1">
                    <!-- ✅ Fixed image path handling -->
                    <?php 
                        $imagePath = !empty($row['image']) ? "../" . htmlspecialchars($row['image']) : "../includes/uploads/courses/default.png";


                    ?>
                    <img src="<?php echo $imagePath; ?>" 
                        alt="<?php echo htmlspecialchars($row['course_name']); ?>">


                    <div class="card_content1">
                        <h3><?php echo htmlspecialchars($row['course_name']); ?></h3>

                        <!-- ✅ Description safely pulled from DB -->
                        <p>
                            <?php 
                            $desc = htmlspecialchars($row['course_des']);
                            echo strlen($desc) > 120 ? substr($desc, 0, 120) . "..." : $desc; 
                            ?>
                        </p>
                        <br>

                        <a href="course_details.php?id=<?php echo $row['course_id']; ?>" class="btn_1">
                            <button class="btn_course">Read More</button>
                        </a>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p style="text-align:center;">No courses available at the moment.</p>
        <?php } ?>
    </div>
</div>

</body>
</html>
