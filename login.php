<?php
session_start();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connect to database
    $conn = new mysqli("localhost", "root", "", "a_winventory_system");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize input
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Query user
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND role = ?");
    $stmt->bind_param("ss", $username, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    $login_error = "";

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
       if (password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];  // ✅ Save the user ID
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];

    if ($user['role'] === 'admin') {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        header("Location: user_dashboard.php");
        exit();
    }


        } else {
            $login_error = "Incorrect password.";
        }
    } else {
        $login_error = "User not found or role mismatch.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(to right,rgb(73, 61, 27),rgb(191, 245, 255));
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: white;
            display: flex;
            width: 900px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .left {
            flex: 1;
            background: linear-gradient(135deg, #6B4226, #A0522D);
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .left h1 {
            font-size: 36px;
        }

        .right {
            flex: 1;
            padding: 40px;
        }

        .right h2 {
            margin-bottom: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: #6B4226;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }

        .btn:hover {
            background: #4E2E1A;
        }

        .alt-login {
            background: none;
            border: 1px solid #333;
            margin-top: 10px;
        }

        .remember {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .small-text {
            font-size: 14px;
            text-align: center;
            margin-top: 20px;
        }

        a {
            color: #A0522D;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            margin-bottom: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="left">
        <h1>WELCOME</h1>
        <p><br><h2>Arts in Woods Furnitures</h2></p>
    </div>
    <div class="right">
        <h2>Sign in</h2>
        <?php if (!empty($login_error)) echo "<div class='error'>$login_error</div>"; ?>
        <form method="POST" action="">
            <!-- Role Selection on Top -->
            <div class="form-group">
                <label for="role"><strong>Choose Method to Log In:</strong></label>
                <select name="role" id="role" required>
                    <option value="">Select Role</option>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
            </div>
            <!-- Username Field -->
            <div class="form-group">
                <input type="text" name="username" placeholder="User Name" required>
            </div>
            <!-- Password Field -->
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <!-- Remember Me -->
            <div class="remember">
                <label><input type="checkbox"> Remember me</label>
                <a href="#">Forgot Password?</a>
            </div>
            <!-- Submit Buttons -->
            <div class="form-group">
                <button class="btn" type="submit">Sign in</button>
                <button class="btn alt-login" type="button">Sign in with other</button>
            </div>
            <!-- Registration Link -->
            <div class="small-text">
                Don’t have an account? <a href="register.php">Sign Up</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
