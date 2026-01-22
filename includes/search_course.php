<?php
include('../includes/connect.php');

// Handle filters
$conditions = [];
$params = [];
$types = "";

if (!empty($_GET['category'])) {
    $conditions[] = "c.category = ?";
    $params[] = $_GET['category'];
    $types .= "s";
}

if (!empty($_GET['location'])) {
    $conditions[] = "c.location = ?";
    $params[] = $_GET['location'];
    $types .= "s";
}

if (!empty($_GET['duration'])) {
    $conditions[] = "c.duration = ?";
    $params[] = $_GET['duration'];
    $types .= "i";
}

if (!empty($_GET['instructor'])) {
    $conditions[] = "u.username = ?";
    $params[] = $_GET['instructor'];
    $types .= "s";
}

// Build SQL query
$sql = "SELECT c.*, u.username AS instructor 
        FROM courses c 
        LEFT JOIN users u ON c.user_id = u.user_id";

if ($conditions) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Fetch filter dropdown data
$categories = $conn->query("SELECT DISTINCT category FROM courses");
$locations = $conn->query("SELECT DISTINCT location FROM courses");
$durations = $conn->query("SELECT DISTINCT duration FROM courses ORDER BY duration ASC");
$instructors = $conn->query("SELECT DISTINCT u.username FROM users u JOIN courses c ON u.user_id = c.user_id WHERE u.role='Instructor'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Search Courses | SkillPro Institute</title>
<link rel="stylesheet" href="../style1.css">
<link rel="stylesheet" href="../css/search_courses.css">
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
              <li><a href="./contact.php">CONTACT</a></li>
          </ul>
      </div>
  </div>

  <!-- Filter Section -->
  <div class="filter-container">
      <h2>Filter Courses</h2>
      <form method="GET" class="filter-form">
          <select name="category">
              <option value="">Select Category</option>
              <?php while($cat = $categories->fetch_assoc()) { ?>
                  <option value="<?= htmlspecialchars($cat['category']); ?>" 
                      <?= (($_GET['category'] ?? '') == $cat['category']) ? 'selected' : ''; ?>>
                      <?= htmlspecialchars($cat['category']); ?>
                  </option>
              <?php } ?>
          </select>

          <select name="location">
              <option value="">Select Location</option>
              <?php while($loc = $locations->fetch_assoc()) { ?>
                  <option value="<?= htmlspecialchars($loc['location']); ?>" 
                      <?= (($_GET['location'] ?? '') == $loc['location']) ? 'selected' : ''; ?>>
                      <?= htmlspecialchars($loc['location']); ?>
                  </option>
              <?php } ?>
          </select>

          <select name="duration">
              <option value="">Select Duration (weeks)</option>
              <?php while($dur = $durations->fetch_assoc()) { ?>
                  <option value="<?= $dur['duration']; ?>" 
                      <?= (($_GET['duration'] ?? '') == $dur['duration']) ? 'selected' : ''; ?>>
                      <?= $dur['duration']; ?>
                  </option>
              <?php } ?>
          </select>

          <select name="instructor">
              <option value="">Select Instructor</option>
              <?php while($ins = $instructors->fetch_assoc()) { ?>
                  <option value="<?= htmlspecialchars($ins['username']); ?>" 
                      <?= (($_GET['instructor'] ?? '') == $ins['username']) ? 'selected' : ''; ?>>
                      <?= htmlspecialchars($ins['username']); ?>
                  </option>
              <?php } ?>
          </select>

          <button type="submit" class="btn-filter">Apply Filters</button>
      </form>
  </div>

  <!-- Course Cards -->
  <div class="card_container1">
      <div class="title_courses">Filtered Courses</div>

      <?php if ($result->num_rows > 0) { ?>
          <?php while ($row = $result->fetch_assoc()) { ?>
              <div class="card1">
                  <img src="../<?= htmlspecialchars($row['image']); ?>" alt="<?= htmlspecialchars($row['course_name']); ?>">
                  <div class="card_content1">
                      <h3><?= htmlspecialchars($row['course_name']); ?></h3>
                      <p><?= htmlspecialchars(substr($row['course_des'], 0, 100)) . '...'; ?></p>
                      <p><strong>Instructor:</strong> <?= htmlspecialchars($row['instructor']); ?></p>
                      <p><strong>Location:</strong> <?= htmlspecialchars($row['location']); ?></p>
                      <a href="./course_details.php?id=<?= $row['course_id']; ?>" class="btn-course">View Details</a>
                  </div>
              </div>
          <?php } ?>
      <?php } else { ?>
          <p style="text-align:center;">No courses found matching your filters.</p>
      <?php } ?>
  </div>
</div>

</body>
</html>
