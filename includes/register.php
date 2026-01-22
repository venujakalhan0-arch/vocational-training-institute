<?php 
session_start();
session_regenerate_id(true);
include('./connect.php');
error_reporting(E_ALL);

if (isset($_POST['signup'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $role = trim($_POST['role']);
    $gender = $_POST['gender'];
    $created_at = date("Y-m-d H:i:s");

    // ✅ Fix: Role capitalization (DB uses Admin, Instructor, Student)
    $role = ucfirst(strtolower($role));

    // Check password confirmation
    if ($password !== $confirm_password) {
        echo '<script>alert("Passwords do not match."); window.history.back();</script>';
        exit();
    }

    // Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Handle profile image upload
$target_dir = "../includes/uploads/";
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true); // make uploads folder if missing
}

$profileImage = null; // default empty
if (isset($_FILES["profile_image"]) && $_FILES["profile_image"]["error"] === 0) {
    $image_name = basename($_FILES["profile_image"]["name"]);
    // sanitize + unique filename
    $newFileName = time() . "_" . preg_replace("/[^a-zA-Z0-9\._-]/", "_", $image_name);
    $target_file = $target_dir . $newFileName;

    if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
        // save relative path into DB
        $profileImage = "./includes/uploads/" . $newFileName;
    }
}



    // Prepare SQL insert
    $stmt = $conn->prepare("INSERT INTO users (username, role, email, phone_num, password, gender, image_user, created_at) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssssssss", $username, $role, $email, $phone, $hashed_password, $gender, $profileImage, $created_at);

    if ($stmt->execute()) {
        echo '<script>alert("Registration successful! Please login."); window.location="login.php";</script>';
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
    <link rel="stylesheet" href="../style1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" 
          integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" 
          crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

<div class="main">

    <!-- Register Form -->
    <div class="container1">
        <div class="title">Registration</div>
        <form action="register.php" method="POST" class="form2" enctype="multipart/form-data">
            <div class="user_details">
                <div class="input_box">
                    <span class="details">Full Name</span>
                    <input type="text" name="username" placeholder="Enter your name" required>
                </div>

                <div class="input_box">
                    <span class="details">Select Your Role</span>
                    <select name="role" class="user_type" required>
                        <option value="">Select role</option>
                        <option value="Admin">Admin</option>
                        <option value="Instructor">Instructor</option>
                        <option value="Student">Student</option>
                    </select>
                </div>

                <div class="input_box">
                    <span class="details">Email</span>
                    <input type="email" name="email" placeholder="Enter your email" required>
                </div>

                <div class="input_box">
                    <span class="details">Phone Number</span>
                    <input type="text" name="phone" placeholder="Enter your number" required>
                </div>

                <div class="input_box">
                    <span class="details">Password</span>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>

                <div class="input_box">
                    <span class="details">Confirm Password</span>
                    <input type="password" name="confirm_password" placeholder="Confirm your password" required>
                </div>

                <div class="input_photo">
                    <span class="details">Choose your photo</span><br>
                    <input type="file" name="profile_image" accept="image/*" required>
                </div>
            </div>

            <div class="gender-details">
                <input type="radio" name="gender" value="Male" id="dot-1" required>
                <input type="radio" name="gender" value="Female" id="dot-2">
                <input type="radio" name="gender" value="Prefer not to say" id="dot-3">
                <span class="gender-title">Gender</span>
                <div class="category">
                    <label for="dot-1"><span class="dot one"></span><span class="gender">Male</span></label>
                    <label for="dot-2"><span class="dot two"></span><span class="gender">Female</span></label>
                    <label for="dot-3"><span class="dot three"></span><span class="gender">Prefer not to say</span></label>
                </div>
            </div>

            <div class="button_reg">
                <input type="submit" name="signup" value="Register">
            </div>
        </form>

        <br>
        <p>Already have an account? <a href="./login.php">Login here</a></p>
        <p><a href="../index.php">Back to Home</a></p>
    </div>
</div>

</body>
</html>
