session_start();
$user_id = $_SESSION['user_id']; // Assuming you store user_id in session after login

// Example Insert Query
$stmt = $conn->prepare("INSERT INTO orders (user_id, customer_name, products, amount, status, image, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("issdss", $user_id, $customer_name, $products, $amount, $status, $image_name);
$stmt->execute();
