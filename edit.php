<?php
include 'db.php';
$id = $_GET['id'];
$sql = "SELECT * FROM products WHERE id=$id";
$result = $conn->query($sql);
$product = $result->fetch_assoc();
?>

<form action="edit_process.php" method="POST" class="space-y-4 p-4 bg-white rounded shadow">
  <input type="hidden" name="id" value="<?= $product['id'] ?>">
  <input type="text" name="name" value="<?= $product['name'] ?>" class="w-full border p-2 rounded" required>
  <input type="number" name="available" value="<?= $product['available'] ?>" class="w-full border p-2 rounded" required>
  <input type="number" name="sold" value="<?= $product['sold'] ?>" class="w-full border p-2 rounded" required>
  <select name="status" class="w-full border p-2 rounded">
    <option <?= $product['status']=='High'?'selected':'' ?> value="High">High</option>
    <option <?= $product['status']=='Normal'?'selected':'' ?> value="Normal">Normal</option>
    <option <?= $product['status']=='Low'?'selected':'' ?> value="Low">Low</option>
  </select>
  <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Update Product</button>
</form>
