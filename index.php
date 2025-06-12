<?php
include 'db.php';

function getBadgeColor($status) {
  return match($status) {
    'High' => 'bg-green-500 text-white',
    'Normal' => 'bg-yellow-500 text-white',
    'Low' => 'bg-red-500 text-white',
    default => 'bg-gray-500 text-white',
  };
}

$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Inventory</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="max-w-7xl mx-auto py-8 px-4">
  
  <!-- Header with Buttons -->
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Inventory</h1>
    <div class="flex space-x-4">
      <a href="add.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add Product</a>
      <a href="dashboard.php" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Return</a>
    </div>
  </div>

  <!-- Success message -->
  <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
      Product added successfully!
    </div>
  <?php endif; ?>

  <!-- Product Grid -->
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    <?php while ($product = $result->fetch_assoc()): ?>
      <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300 overflow-hidden">
        <div class="relative">
          <img src="uploaded_img/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-40 object-cover">
          <span class="absolute top-2 left-2 px-2 py-1 text-xs font-medium rounded <?= getBadgeColor($product['status']) ?>">
            <?= htmlspecialchars($product['status']) ?>
          </span>
        </div>
        <div class="p-4">
          <h3 class="text-lg font-semibold mb-1"><?= htmlspecialchars($product['name']) ?></h3>
          <p class="text-sm text-gray-600 mb-1">Price: ₱<?= number_format($product['price'], 2) ?></p>
          <p class="text-sm text-gray-600 mb-1">Units Available: <strong><?= (int)$product['available'] ?></strong></p>
          <p class="text-sm text-gray-600 mb-1">Units Sold: <strong><?= (int)$product['sold'] ?></strong></p>
          <div class="flex space-x-4 mt-3">
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
