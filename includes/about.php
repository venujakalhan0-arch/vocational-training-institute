<?php
include('./connect.php');

// Fetch instructors from DB
$query = "SELECT username, email, image_user FROM users WHERE role = 'Instructor'";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>About Us | SkillPro Institute</title>

  <!-- Main site stylesheet -->
  <link rel="stylesheet" href="../style1.css">

  <!-- About page stylesheet -->
  <link rel="stylesheet" href="../css/about.css">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" 
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" 
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
  <div class="main inner-page">

    <!-- Navbar -->
    <div class="navbar">
      <div class="icon">
        <a href="../index.php"><h2 class="logo">Skill<span>Pro.</span></h2></a>
      </div>

      <div class="menu">
        <ul>
          <li><a href="../index.php">HOME</a></li>
          <li><a href="./about.php" class="active">ABOUT</a></li>
          <li>
            <a href="./courses.php">COURSES</a>
          </li>
          <li><a href="./event.php">EVENTS</a></li>
          <li><a href="./contact.php">CONTACT</a></li>
        </ul>
      </div>

      <div class="search">
    <a href="./includes/search_course.php">
        <button class="btn">Search Courses</button>
    </a>
</div>
    </div>

    <!-- About Section -->
    <section class="about-section">
      <div class="about-container">
        <h2>About <span>SkillPro Institute</span></h2>

        <p>
          SkillPro Institute is a <strong>TVEC-certified vocational training center</strong> in Sri Lanka, 
          dedicated to empowering students with practical skills for real-world careers. 
          Established to bridge the skills gap, we offer modern, industry-oriented courses in 
          ICT, Plumbing, Welding, and Hotel Management.
        </p>

        <p>
          Our expert instructors, flexible learning modes (Online / On-site), and hands-on training 
          ensure that students not only gain knowledge but also the confidence to excel in their professions. 
          Whether you're starting your career or looking to upgrade your skills, SkillPro provides a 
          pathway to success.
        </p>

        <p>
          With branches in Colombo, Kandy, and Matara, our mission is to make quality technical 
          education accessible across Sri Lanka. Join us to become part of a growing community 
          of skilled professionals driving the nation forward.
        </p>
      </div>
    </section>

    <!-- Instructor Section (Dynamic) -->
    <section class="team-section">
      <h2>Meet Our Instructors</h2>
      <div class="team-container">
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): 
            $imagePath = !empty($row['image_user']) ? "../" . htmlspecialchars($row['image_user']) : "../includes/uploads/default_user.png";
          ?>
            <div class="team-card">
              <img src="<?php echo $imagePath; ?>" alt="Instructor">
              <h3><?php echo htmlspecialchars($row['username']); ?></h3>
              <p><?php echo htmlspecialchars($row['email']); ?></p>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p>No instructors found.</p>
        <?php endif; ?>
      </div>
    </section>

  </div>
</body>
</html>
