<?php
session_start();
session_unset();   // clear all session variables
session_destroy(); // destroy the session completely
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Logged Out</title>
  <link rel="stylesheet" href="../css/logout.css">
</head>
<body>
  <div class="logout-container">
    <div class="logout-card">
      <h1>You have been logged out</h1>
      <p>Thank you for using SkillPro Institute portal.</p>
      <a href="../includes/login.php" class="btn">Login Again</a>
      <a href="../index.php" class="btn btn-secondary">Back to Home</a>
    </div>
  </div>
</body>
</html>
