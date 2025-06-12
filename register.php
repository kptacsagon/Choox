<?php
$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Connect to MySQL
    $conn = new mysqli("localhost", "root", "", "a_winventory_system");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get form data safely
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $role = isset($_POST["role"]) ? $_POST["role"] : 'user'; // Default to 'user' if not provided

    // Check if username exists
    $check = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = "Username already taken!";
    } else {
        // Hash the password
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user with role
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hash, $role);

        if ($stmt->execute()) {
            $success = "Account created successfully! You can now <a href='login.php'>Sign in</a>.";
        } else {
            $error = "Something went wrong. Try again.";
        }

        $stmt->close();
    }

    $check->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(to right, #0052cc, #007bff);
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
            background: linear-gradient(135deg, #004bb5, #007bff);
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
            background: #004bb5;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }

        .btn:hover {
            background: #003a91;
        }

        .small-text {
            font-size: 14px;
            text-align: center;
            margin-top: 20px;
        }

        .message {
            font-size: 14px;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .success {
            background: #d4edda;
            color: #155724;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="left">
        <h1>WELCOME</h1>
        <p>Arts in Woods Furnitures<br>Create your account below</p>
    </div>
    <div class="right">
        <h2>Sign Up</h2>

        <?php if ($success): ?>
            <div class="message success"><?= $success ?></div>
        <?php elseif ($error): ?>
            <div class="message error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <!-- Role Selection -->
            <div class="form-group">
                <label for="role"><strong>Select Account Type:</strong></label>
                <select name="role" id="role" required>
                    <option value="">Choose Role</option>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
            </div>
            <div class="form-group">
                <input type="text" name="username" placeholder="User Name" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <button class="btn" type="submit">Register</button>
            </div>
            <div class="small-text">
                Already have an account? <a href="login.php">Sign in</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
