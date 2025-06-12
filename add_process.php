<?php
include 'db.php';

$name = $_POST['name'];
$price = $_POST['price'];
$available = $_POST['available'];
$sold = $_POST['sold'];
$status = $_POST['status'];

$image = $_FILES['image']['name'];
$tmp_name = $_FILES['image']['tmp_name'];
$target = "uploaded_img/" . basename($image);

// Move uploaded file
if (!move_uploaded_file($tmp_name, $target)) {
    die("Failed to upload image.");
}

// Save to database
$sql = "INSERT INTO products (name, price, available, sold, status, image) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sdiiss", $name, $price, $available, $sold, $status, $image);

if ($stmt->execute()) {
    header("Location: inventory.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}
?>
