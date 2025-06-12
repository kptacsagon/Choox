<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $status = $_POST['status'] ?? '';
    $customer = $_POST['customer'] ?? '';
    $products = $_POST['products'] ?? '';
    $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0.00;

    // Sanitize input
    $customer = htmlspecialchars(trim($customer));
    $products = htmlspecialchars(trim($products));

    // Optional image upload
    if (!empty($_FILES['product_image']['name'])) {
        $image_name = basename($_FILES['product_image']['name']);
        $target_path = "uploaded_img/" . $image_name;

        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_path)) {
            // ✅ Update with image
            $stmt = $conn->prepare("UPDATE orders SET status=?, customer_name=?, products=?, amount=?, image=? WHERE id=?");
            $stmt->bind_param("sssssi", $status, $customer, $products, $amount, $image_name, $id);
        } else {
            die("Image upload failed.");
        }
    } else {
        // ✅ Update without image
        $stmt = $conn->prepare("UPDATE orders SET status=?, customer_name=?, products=?, amount=? WHERE id=?");
        $stmt->bind_param("sssdi", $status, $customer, $products, $amount, $id);
    }

    // ✅ Execute update
    if ($stmt->execute()) {
        echo "<script>alert('Order updated successfully!'); window.location.href='orders.php';</script>";
    } else {
        echo "<p style='color:red;'>Failed to update order: " . $stmt->error . "</p>";
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}
?>
