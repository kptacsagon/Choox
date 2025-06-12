<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}

include 'db.php';

function getBadgeColor($status) {
    return match($status) {
        'High' => 'badge-high',
        'Normal' => 'badge-normal',
        'Low' => 'badge-low',
        default => 'badge-default',
    };
}

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

if ($result->num_rows === 0) {
    echo "<p class='max-w-screen-xl mx-auto px-4 py-6 text-center text-gray-600'>No products found in the database.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <style>
        .logout-button {
            padding: 10px 20px;
            background-color: #ff4d4d;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .logout-button:hover {
            background-color: #e60000;
        }
        .badge-high { background-color: #f87171; color: white; }
        .badge-normal { background-color: #34d399; color: white; }
        .badge-low { background-color: #fbbf24; color: white; }
        .badge-default { background-color: #9ca3af; color: white; }
    </style>
</head>
<body class="bg-green-100 font-sans min-h-screen">

    <div class="max-w-screen-xl mx-auto px-4 py-6">
        <!-- User Header -->
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 space-y-4 md:space-y-0 bg-white p-6 rounded-lg shadow">
            <h1 class="text-4xl font-bold text-gray-800">Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
            <a href="logout.php" class="logout-button">Logout</a>
        </div>

        <!-- Page Header -->
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 space-y-4 md:space-y-0">
            <h1 class="text-4xl font-bold text-gray-800">Products</h1>
            <div class="flex space-x-4">
                <a href="order_2.php" class="bg-blue-600 text-white px-6 py-2 rounded-lg shadow hover:bg-blue-700 transition">Order</a>
                
            </div>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php while ($product = $result->fetch_assoc()): ?>
                <?php
                    $status = $product['status'] ?? 'Normal';
                    $available = $product['available'] ?? 0;
                    $sold = $product['sold'] ?? 0;
                    $price = $product['price'] ?? 0.00;
                ?>
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition transform hover:-translate-y-1">
                    <div class="relative">
                        <img src="uploaded_img/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-48 object-cover">
                        <span class="absolute top-2 left-2 px-3 py-1 text-xs font-medium rounded-full <?= getBadgeColor($status) ?>">
                            <?= htmlspecialchars($status) ?>
                        </span>
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold mb-2"><?= htmlspecialchars($product['name']) ?></h3>
                        <p class="text-sm text-gray-600 mb-1">Price: ₱<?= number_format($price, 2) ?></p>
                        <p class="text-sm text-gray-600 mb-1">Units Available: <strong><?= (int)$available ?></strong></p>
                        
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

</body>
</html>
