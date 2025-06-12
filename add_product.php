<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Add Product</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
  <h2 class="text-2xl font-bold mb-6">Add New Product</h2>
  
  <form action="add_process.php" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md w-full max-w-md">
    <div class="mb-4">
      <input type="text" name="name" placeholder="Product Name" required class="w-full border p-2 rounded">
    </div>
    <div class="mb-4">
      <input type="number" step="0.01" name="price" placeholder="Price" required class="w-full border p-2 rounded">
    </div>
    <div class="mb-4">
      <input type="number" name="stock" placeholder="Stock" required class="w-full border p-2 rounded">
    </div>
    <div class="mb-4">
      <input type="file" name="image" required class="w-full">
    </div>
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Product</button>
  </form>
</body>
</html>
