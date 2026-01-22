<?php
include('./includes/connect.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
  crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Main stylesheet -->
  <link rel="stylesheet" href="./style1.css">

  <title>SkillPro Institute</title>
</head>
<body>
  <div class="main">
    <div class="navbar">
      <!-- Logo -->
      <div class="icon">
        <a href="./index.php"><h2 class="logo">Skill<span>Pro.</span></h2></a>
      </div>

      <!-- Menu -->
      <div class="menu">
        <ul>
          <li><a href="./index.php">HOME</a></li>
          <li><a href="./includes/about.php">ABOUT</a></li>
          <li><a href="./includes/courses.php">COURSES</a></li>
          <li><a href="./includes/event.php">EVENTS</a></li>
          <li><a href="./includes/contact.php">CONTACT</a></li>
        </ul>
      </div>

      <!-- 🔍 Search Button (no bar) -->
      <!--Start: Search bar Area-->
<div class="search">
    <a href="./includes/search_course.php">
        <button class="btn">Search Courses</button>
    </a>
</div>
<!--End: Search bar Area-->

    </div>

    <!-- Content -->
    <div class="content">
      <h1>SkillPro Institute</h1>
      <p>
        SkillPro Institute is a leading TVEC-registered training center in Sri Lanka with branches in Colombo, Kandy, and Matara. <br>
        We offer practical courses in ICT, Plumbing, Welding, Hotel Management, and more. <br>
        Explore courses, check schedules, register online, and stay updated with events.
      </p>
      <a href="./includes/register.php"><button class="cn">Register Now</button></a>
      <a href="./includes/login.php"><button class="cn1">Login</button></a>
    </div>
    <hr class="hr2">
  </div>

  <!-- Footer -->
  <footer>
    <div class="row">
      <div class="col">
        <h3 class="logo2">Skill<span class="span2">Pro.</span></h3>
        <p>Explore courses, register online, check schedules, and stay updated with the latest events.</p>
      </div>

      <div class="col">
        <h3>Branches <div class="underline"><span class="span3"></span></div></h3>
        <p>Colombo</p>
        <p>Kandy</p>
        <p>Matara</p>
        <p>+94 76 123 4567</p>
        <p class="email-id">info.skill@pro.lk</p>
      </div>

      <div class="col">
        <h3>Quick Links <div class="underline"><span class="span3"></span></div></h3>
        <div class="ft">
          <p><a href="./index.php">Home</a></p>
          <p><a href="./includes/about.php">About</a></p>
          <p><a href="./includes/event.php">Events</a></p>
          <p><a href="./includes/contact.php">Contact</a></p>
        </div>
      </div>

      <div class="col">
        <h3>Write to Us <div class="underline"><span class="span3"></span></div></h3>
        <form class="form1">
          <i class="far fa-envelope"></i>
          <input type="text" placeholder="Enter your message" required>
          <button type="submit"><i class="fas fa-arrow-right"></i></button>
        </form>
      </div>
    </div>
    <hr>
    <p class="copyright">SkillPro &copy; 2025 - All Rights Reserved</p>
  </footer>
</body>
</html>
