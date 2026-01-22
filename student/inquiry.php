<?php
session_start();
include('../includes/connect.php');

// Only students allowed
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Student') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 🗑️ Delete inquiry
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM inquiries WHERE inquiry_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $delete_id, $user_id);
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('Inquiry deleted successfully.'); window.location='inquiry.php';</script>";
    exit();
}

// ✉️ Submit new inquiry
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if (!empty($subject) && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO inquiries (user_id, subject, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $subject, $message);
        if ($stmt->execute()) {
            echo "<script>alert('Inquiry submitted successfully!'); window.location='inquiry.php';</script>";
        } else {
            echo "<script>alert('Error submitting inquiry.');</script>";
        }
        $stmt->close();
    }
}

// 📋 Fetch all inquiries for this user
$query = "SELECT inquiry_id, subject, message, reply, created_at 
          FROM inquiries 
          WHERE user_id = ? 
          ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Submit Inquiry</title>
  <link rel="stylesheet" href="../css/inquiry.css">
</head>
<body>
  <div class="inquiry-container">
    <h2>Submit Inquiry</h2>

    <!-- Inquiry submission form -->
    <form method="POST">
      <label>Subject</label>
      <input type="text" name="subject" required>

      <label>Message</label>
      <textarea name="message" rows="5" required></textarea>

      <button type="submit" class="btn-submit">Send Inquiry</button>
      <a href="student_dashboard.php" class="btn-back">Back</a>
    </form>

    <!-- List of previous inquiries -->
    <div class="inquiry-list">
      <h3>Your Submitted Inquiries</h3>

      <?php if ($result->num_rows > 0) { ?>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Subject</th>
              <th>Message</th>
              <th>Reply</th>
              <th>Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
              <tr>
                <td><?php echo htmlspecialchars($row['inquiry_id']); ?></td>
                <td><?php echo htmlspecialchars($row['subject']); ?></td>
                <td><?php echo htmlspecialchars($row['message']); ?></td>
                <td>
                  <?php if (!empty($row['reply'])) { ?>
                    <span class="reply-received"><?php echo htmlspecialchars($row['reply']); ?></span>
                  <?php } else { ?>
                    <span class="reply-pending">Pending reply</span>
                  <?php } ?>
                </td>
                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                <td>
                  <a href="?delete_id=<?php echo $row['inquiry_id']; ?>" 
                     class="btn-delete"
                     onclick="return confirm('Are you sure you want to delete this inquiry?');">
                    Clear
                  </a>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      <?php } else { ?>
        <p style="text-align:center;">You haven't submitted any inquiries yet.</p>
      <?php } ?>
    </div>
  </div>
</body>
</html>
