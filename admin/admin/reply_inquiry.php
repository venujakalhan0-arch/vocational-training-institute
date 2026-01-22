<?php
session_start();
include('../includes/connect.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT i.*, u.username, u.email FROM inquiries i JOIN users u ON i.user_id=u.user_id WHERE inquiry_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$inquiry = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reply = trim($_POST['reply']);
    $stmt2 = $conn->prepare("UPDATE inquiries SET reply=? WHERE inquiry_id=?");
    $stmt2->bind_param("si", $reply, $id);
    $stmt2->execute();
    echo "<script>alert('Reply saved successfully!'); window.location='manage_inquiries.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reply Inquiry</title>
  <link rel="stylesheet" href="../css/reply_inquiry.css">
</head>
<body>
  <div class="reply-container">
    <h2>Reply to Inquiry</h2>
    <p><strong>From:</strong> <?php echo htmlspecialchars($inquiry['username']); ?> (<?php echo htmlspecialchars($inquiry['email']); ?>)</p>
    <p><strong>Subject:</strong> <?php echo htmlspecialchars($inquiry['subject']); ?></p>
    <p><strong>Message:</strong> <?php echo htmlspecialchars($inquiry['message']); ?></p>

    <form method="POST">
      <label>Reply</label>
      <textarea name="reply" rows="5" required><?php echo htmlspecialchars($inquiry['reply'] ?? ''); ?></textarea>
      <button type="submit" class="btn-submit">Save Reply</button>
      <a href="manage_inquiries.php" class="btn-back">Cancel</a>
    </form>
  </div>
</body>
</html>
