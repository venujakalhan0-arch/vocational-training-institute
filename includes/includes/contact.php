<?php
include('../includes/connect.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Us - SkillPro Institute</title>
  <link rel="stylesheet" href="../style1.css">
  <link rel="stylesheet" href="../css/contact.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" 
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" 
        crossorigin="anonymous" referrerpolicy="no-referrer" />
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
          <li><a href="./courses.php">COURSES</a></li>
          <li><a href="./event.php">EVENTS</a></li>
          <li><a href="./contact.php" class="active">CONTACT</a></li>
        </ul>
      </div>

      <div class="search">
        <a href="./search_course.php">
          <button class="btn">Search Courses</button>
        </a>
      </div>
    </div>

    <!-- Contact Section -->
    <div class="contact-container">
      <h2>Contact Us</h2>
      <p>Have a question or want to know more about our courses? We’d love to hear from you!</p>

      <div class="contact-grid">
        <!-- Left: Info -->
        <div class="contact-info">
          <h3>📍 Our Branches</h3>
          <p><strong>Colombo:</strong> 123 Main Street, Colombo 07</p>
          <p><strong>Kandy:</strong> 55 Kings Street, Kandy</p>
          <p><strong>Matara:</strong> 21 Central Road, Matara</p>
          <p><strong>Phone:</strong> +94 76 123 4567</p>
          <p><strong>Email:</strong> info.skill@pro.lk</p>
          
        </div>

        <!-- Right: Inquiry Form -->
        <div class="contact-form">
          <h3>📝 Submit an Inquiry</h3>
          <form method="POST" action="submit_inquiry.php">
            <label>Your Name</label>
            <input type="text" name="name" placeholder="Enter your full name" required>

            <label>Your Email</label>
            <input type="email" name="email" placeholder="Enter your email" required>

            <label>Subject</label>
            <input type="text" name="subject" placeholder="Subject of your inquiry" required>

            <label>Message</label>
            <textarea name="message" rows="5" placeholder="Type your message..." required></textarea>

            <button type="submit" class="btn-submit"><i class="fa fa-paper-plane"></i> Submit Inquiry</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
