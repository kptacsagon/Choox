<?php
include 'db.php';

if (!isset($_GET['id'])) {
    die("Missing order ID.");
}

$order_id = (int) $_GET['id'];

// Fetch the order
$sql = "SELECT * FROM orders WHERE id = $order_id";
$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
    die("Order not found.");
}

$order = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Order #<?php echo $order_id; ?></title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 40px; }
        form { background: #fff; padding: 20px; max-width: 500px; margin: auto; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        label { font-weight: bold; margin-top: 10px; display: block; }
        input, select, textarea { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px; }
        button { background: #007bff; color: white; border: none; padding: 10px 15px; margin-top: 20px; cursor: pointer; border-radius: 5px; }
        button:hover { background: #0056b3; }
        a { display: block; margin-top: 20px; text-align: center; color: #007bff; text-decoration: none; }
    </style>
</head>
<body>

<h2>Update Order #<?php echo $order_id; ?></h2>

<form method="POST" action="update_order_process.php" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $order_id; ?>">

    <label>Status:</label>
    <select name="status" required>
        <option value="New Order" <?php if ($order['status'] == 'New Order') echo 'selected'; ?>>New Order</option>
        <option value="In Progress" <?php if ($order['status'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
        <option value="Shipped" <?php if ($order['status'] == 'Shipped') echo 'selected'; ?>>Shipped</option>
        <option value="Delivered" <?php if ($order['status'] == 'Delivered') echo 'selected'; ?>>Delivered</option>
    </select>

    <label>Customer:</label>
   <input type="text" name="customer"
       value="<?php echo isset($order['customer']) ? htmlspecialchars($order['customer']) : ''; ?>" required>

    <label>Product(s):</label>
    <textarea name="products" required><?php echo htmlspecialchars($order['products']); ?></textarea>

    <label>Amount (₱):</label>
    <input type="number" step="0.01" name="amount" value="<?php echo htmlspecialchars($order['amount']); ?>" required>

    <label>Change Product Image (optional):</label>
    <input type="file" name="product_image">

    <button type="submit">Update Order</button>
</form>

<a href="orders.php">← Back to Orders Page</a>

</body>
</html>
