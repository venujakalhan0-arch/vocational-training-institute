<?php 
session_start();
include('./connect.php');
error_reporting(E_ALL);

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"])) {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $user_type = trim($_POST["user_type"]);
    $redirect = $_POST['redirect'] ?? ''; // get redirect if available

    if (empty($email) || empty($password) || empty($user_type)) {
        echo '<script>alert("Please fill in all fields.");</script>';
    } else {
        $stmt = $conn->prepare("SELECT user_id, username, role, email, password FROM users WHERE email = ? AND role = ?");
        $stmt->bind_param("ss", $email, $user_type);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id']; 
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // ✅ Redirect to previous page if exists
                if (!empty($redirect)) {
                    header("Location: $redirect");
                    exit;
                }

                // Otherwise, normal dashboard redirection by role
                switch ($user['role']) {
                    case 'Admin':
                        header("Location: ../admin/admin_dashboard.php");
                        exit;
                    case 'Instructor':
                        header("Location: ../instructor/instructor_dashboard.php");
                        exit;
                    case 'Student':
                        header("Location: ../student/student_dashboard.php");
                        exit;
                    default:
                        echo '<script>alert("Unknown user role.");</script>';
                }
            } else {
                echo '<script>alert("Incorrect password.");</script>';
            }
        } else {
            echo '<script>alert("No user found with this email and role.");</script>';
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../style1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" 
          integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" 
          crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

<div class="main">
    <div class="container2">
        <div class="title_login">Login</div>
        <form action="./login.php<?php echo isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : ''; ?>" method="POST" class="form2">
            <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_GET['redirect'] ?? ''); ?>">

            <div class="user_login">
                <div class="input_box_login">
                    <span class="details_login">Select Your Role</span>
                    <select name="user_type" class="user_type" required>
                        <option value="">Select role</option>
                        <option value="Admin">Admin</option>
                        <option value="Instructor">Instructor</option>
                        <option value="Student">Student</option>
                    </select>
                </div>

                <div class="input_box_login">
                    <span class="details_login">Email</span>
                    <input type="email" name="email" placeholder="Enter your email" required>
                </div>

                <div class="input_box_login">
                    <span class="details_login">Password</span>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>
            </div>

            <div class="button_reg_login">
                <input type="submit" name="login" value="Login">
            </div>
        </form>

        <br>
        <p>If you don't have an account? <a href="./register.php">Register here</a></p>
        <p>Click here <a href="../index.php">Back to Home</a></p>
    </div>
</div>

</body>
</html>
