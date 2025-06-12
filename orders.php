<?php
$mysqli = new mysqli("localhost", "root", "", "a_winventory_system");
if ($mysqli->connect_error) die("Connection failed: " . $mysqli->connect_error);

// Handle deletion
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);

    // Fetch the order details first
    $order_result = $mysqli->query("SELECT products, quantity FROM orders WHERE id = $delete_id");
    $order = $order_result->fetch_assoc();

    if ($order) {
        $product_name = trim($order['products']); // Assuming one product per order
        $quantity = intval($order['quantity']); // Correct quantity field

        // Restore product stock
        $mysqli->query("UPDATE products SET units_available = units_available + $quantity, units_sold = units_sold - $quantity WHERE name = '$product_name'");
    }

    // Delete the order
    $mysqli->query("DELETE FROM orders WHERE id = $delete_id");

    header("Location: orders.php");
    exit();
}

$result = $mysqli->query("SELECT * FROM orders ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Orders</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">

    <div class="max-w-7xl mx-auto bg-white p-8 rounded-2xl shadow-lg">
        <h2 class="text-3xl font-bold mb-6 text-center text-gray-800">Orders</h2>

        <div class="flex justify-between mb-6">
            <a href="create_order.php" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow transition">Create Order</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead>
                    <tr class="bg-gray-200 text-gray-700 text-left">
                        <th class="py-3 px-4">Actions</th>
                        <th class="py-3 px-4">Order ID</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Customer</th>
                        <th class="py-3 px-4">Product(s)</th>
                        <th class="py-3 px-4">Quantity</th>
                        <th class="py-3 px-4">₱ Amount</th>
                        <th class="py-3 px-4">Created at</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="border-t hover:bg-gray-50">
                        <td class="py-3 px-4 space-y-2">
                            <a href="update_order.php?id=<?= $row['id'] ?>" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-3 py-1 rounded shadow">🔧 Update</a>
                            <a href="orders.php?delete=<?= $row['id'] ?>" class="bg-red-600 hover:bg-red-700 text-white text-sm px-3 py-1 rounded shadow" onclick="return confirm('Are you sure you want to delete this order?');">🗑️ Delete</a>
                        </td>
                        <td class="py-3 px-4"><?= $row['id'] ?></td>
                        <td class="py-3 px-4">
                            <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full"><?= $row['status'] ?></span>
                        </td>
                        <td class="py-3 px-4"><?= htmlspecialchars($row['customer_name']) ?></td>
                        <td class="py-3 px-4">
                            <div class="flex flex-col items-start space-y-2">
                                <?php
                                    $imagePath = "uploaded_img/" . $row['image'];
                                    if (!empty($row['image']) && file_exists($imagePath)) {
                                        echo "<img src='$imagePath' class='h-24 rounded-lg border' alt='Product Image'>";
                                    } else {
                                        echo "<img src='uploaded_img/default.png' class='h-24 rounded-lg border' alt='Default Image'>";
                                    }

                                    $products = explode(",", $row['products']);
                                    foreach ($products as $product) {
                                        echo "<span class='bg-gray-200 text-gray-700 text-xs font-medium px-2 py-0.5 rounded'>" . htmlspecialchars(trim($product)) . "</span>";
                                    }
                                ?>
                            </div>
                        </td>
                        <td class="py-3 px-4"><?= (int)$row['quantity'] ?></td>
                        <td class="py-3 px-4">₱<?= number_format($row['amount'], 2) ?></td>
                        <td class="py-3 px-4"><?= $row['created_at'] ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Return Button -->
        <a href="dashboard.php" class="fixed top-5 right-5 bg-gray-700 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-800 transition">
            Return
        </a>
    </div>

</body>
</html>
