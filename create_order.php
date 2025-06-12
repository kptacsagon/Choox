<?php
$mysqli = new mysqli("localhost", "root", "", "a_winventory_system");
if ($mysqli->connect_error) die("Connection failed: " . $mysqli->connect_error);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer = $_POST['customer'];
    $products = $_POST['products'];
    $status = $_POST['status'];
    $amount = $_POST['amount'];
    $image = '';

    if (!empty($_FILES['image']['name'])) {
        $uploadDir = 'uploaded_img/';
        $originalName = basename($_FILES['image']['name']);
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $image = uniqid() . '.' . $extension; // Ensure unique filename
        $uploadFile = $uploadDir . $image;
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile);
    }

    $stmt = $mysqli->prepare("INSERT INTO orders (customer_name, products, status, amount, image, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssds", $customer, $products, $status, $amount, $image);
    $stmt->execute();
    $stmt->close();

    header("Location: orders.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">Create New Order</h2>
    <form method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
        <div class="mb-3">
            <label class="form-label">Customer Name</label>
            <input type="text" name="customer" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Products (comma-separated)</label>
            <input type="text" name="products" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option>New Order</option>
                <option>In Progress</option>
                <option>Completed</option>
                <option>Cancelled</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Amount</label>
            <input type="number" step="0.01" name="amount" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Product Image</label>
            <input type="file" name="image" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Create Order</button>
        <a href="orders.php" class="btn btn-secondary ms-2">Back</a>
    </form>
</div>
</body>
</html>
