// Get selected product details from the database
$product_stmt = $conn->prepare("SELECT price, units_available, units_sold, image FROM products WHERE name = ?");
$product_stmt->bind_param("s", $item);
$product_stmt->execute();
$product_result = $product_stmt->get_result();

if ($product_result->num_rows > 0) {
    $product = $product_result->fetch_assoc();

    if ($product['units_available'] >= $quantity) {
        $price = $product['price'];
        $amount = $price * $quantity;

        // Handle image upload (same as your original)
        $image = "";
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image_tmp = $_FILES['image']['tmp_name'];
            $image_name = basename($_FILES['image']['name']);
            $target_path = "uploaded_img/" . $image_name;

            if (move_uploaded_file($image_tmp, $target_path)) {
                $image = $image_name;
            } else {
                echo "<p style='color:red;'>Failed to upload image.</p>";
            }
        } else {
            $image = $product['image'];
        }

        // Insert order
        $stmt = $conn->prepare("INSERT INTO orders (user_id, customer_name, products, status, amount, image) VALUES (?, ?, ?, 'New Order', ?, ?)");
        $stmt->bind_param("issds", $user_id, $name, $item, $amount, $image);
        $stmt->execute();
        $stmt->close();

        // Deduct stock and update units sold
        $new_units_available = $product['units_available'] - $quantity;
        $new_units_sold = $product['units_sold'] + $quantity;

        $update_stmt = $conn->prepare("UPDATE products SET units_available = ?, units_sold = ? WHERE name = ?");
        $update_stmt->bind_param("iis", $new_units_available, $new_units_sold, $item);
        $update_stmt->execute();
        $update_stmt->close();

        header("Location: user_dashboard.php?order=success");
        exit();

    } else {
        echo "<p style='color:red;'>Insufficient stock available. Only " . $product['units_available'] . " units left.</p>";
    }

} else {
    echo "<p style='color:red;'>Product not found.</p>";
}
