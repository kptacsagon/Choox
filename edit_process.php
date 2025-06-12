<?php
include 'db.php';

$id = $_POST['id'];
$name = $_POST['name'];
$available = $_POST['available'];
$sold = $_POST['sold'];
$status = $_POST['status'];

$sql = "UPDATE products SET name=?, available=?, sold=?, status=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("siisi", $name, $available, $sold, $status, $id);
$stmt->execute();

header("Location: inventory.php");
?>
