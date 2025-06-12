<?php
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
  <title>Inventory</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
</head>
<body class="bg-green-100 font-sans min-h-screen">

  <div class="max-w-screen-xl mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 space-y-4 md:space-y-0">
      <h1 class="text-4xl font-bold text-gray-800">Inventory</h1>
      <div class="flex space-x-4">
        <a href="add.php" class="bg-blue-600 text-white px-6 py-2 rounded-lg shadow hover:bg-blue-700 transition">Add Product</a>
        <a href="dashboard.php" class="bg-gray-600 text-white px-6 py-2 rounded-lg shadow hover:bg-gray-700 transition">Return</a>
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
            <img src="uploaded_img/<?php echo htmlspecialchars($product['image']); ?>" alt="uploaded_image" class="w-full h-48 object-cover">
            <span class="absolute top-2 left-2 px-3 py-1 text-xs font-medium rounded-full <?= getBadgeColor($status) ?>">
              <?= htmlspecialchars($status) ?>
            </span>
          </div>
          <div class="p-4">
            <h3 class="text-lg font-semibold mb-2"><?= htmlspecialchars($product['name']) ?></h3>
            <p class="text-sm text-gray-600 mb-1">Price: ₱<?= number_format($price, 2) ?></p>
            <p class="text-sm text-gray-600 mb-1">Units Available: <strong><?= (int)$available ?></strong></p>
            <p class="text-sm text-gray-600 mb-3">Units Sold: <strong><?= (int)$sold ?></strong></p>
            <div class="flex space-x-4">
              <a href="edit.php?id=<?= (int)$product['id'] ?>" class="text-blue-600 hover:underline">Edit</a>
              <a href="delete.php?id=<?= (int)$product['id'] ?>" class="text-red-600 hover:underline" onclick="return confirm('Delete this product?')">Delete</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>

</body>
</html>
