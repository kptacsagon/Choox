<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "a_winventory_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$products = [
    "Bar Counter" => ["price" => 5000, "image" => "bar_counter.jpg"],
    "Long Bench" => ["price" => 3000, "image" => "long_bench.jpg"],
    "Single Bench" => ["price" => 1500, "image" => "single_bench.jpg"],
    "Center Table" => ["price" => 2500, "image" => "center_table.jpg"]
];

if (isset($_POST['submit'])) {
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $item = $_POST['item'];
    $quantity = $_POST['quantity'];

    $price = $products[$item]['price'];
    $amount = $price * $quantity;

    // Check stock availability
    $stock_check = $conn->prepare("SELECT available FROM products WHERE name = ?");
    $stock_check->bind_param("s", $item);
    $stock_check->execute();
    $stock_result = $stock_check->get_result();
    $stock = $stock_result->fetch_assoc();

    if (!$stock || $stock['available'] < $quantity) {
        echo "<script>alert('Insufficient stock available.'); window.location.href='order_2.php';</script>";
        exit();
    }

    // Deduct stock and update units sold
    $update_stock = $conn->prepare("UPDATE products SET available = available - ?, sold = sold + ? WHERE name = ?");
    $update_stock->bind_param("iis", $quantity, $quantity, $item);
    $update_stock->execute();
    $update_stock->close();

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
        $image = $products[$item]['image'];
    }

    // Insert the order including the quantity
    $stmt = $conn->prepare("INSERT INTO orders (user_id, customer_name, products, quantity, status, amount, image) VALUES (?, ?, ?, ?, 'New Order', ?, ?)");
    $stmt->bind_param("issids", $user_id, $name, $item, $quantity, $amount, $image);
    $stmt->execute();
    $stmt->close();

    header("Location: user_dashboard.php?order=success");
    exit();
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function updateProductDetails() {
            var products = {
                "Bar Counter": { price: 5000, image: "bar_counter.jpg" },
                "Long Bench": { price: 3000, image: "long_bench.jpg" },
                "Single Bench": { price: 1500, image: "single_bench.jpg" },
                "Center Table": { price: 2500, image: "center_table.jpg" }
            };

            var selectedItem = document.querySelector('select[name="item"]').value;
            var product = products[selectedItem];

            document.getElementById('price').innerText = "Price: ₱" + product.price;
            document.getElementById('product-image').src = "uploaded_img/" + product.image;
        }
    </script>
</head>
<body class="bg-green-100 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white p-8 rounded-2xl shadow-lg max-w-md w-full">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Place Your Order</h2>

        <form method="post" action="" enctype="multipart/form-data" class="space-y-5">
            <div>
                <label class="block text-gray-700 mb-2">Your Name:</label>
                <input type="text" name="name" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" />
            </div>

            <div>
                <label class="block text-gray-700 mb-2">Select Item:</label>
                <select name="item" onchange="updateProductDetails()" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="Bar Counter">Bar Counter</option>
                    <option value="Long Bench">Long Bench</option>
                    <option value="Single Bench">Single Bench</option>
                    <option value="Center Table">Center Table</option>
                </select>
            </div>

            <div>
                <span id="price" class="block text-lg font-semibold text-gray-700 mb-2">Price: </span>
                <img id="product-image" src="" alt="Product Image" class="w-full h-48 object-cover border rounded-lg" />
            </div>

            <div>
                <label class="block text-gray-700 mb-2">Quantity:</label>
                <input type="number" name="quantity" min="1" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" />
            </div>

            <div>
                <label class="block text-gray-700 mb-2">Upload Custom Image (optional):</label>
                <input type="file" name="image" accept="image/*" class="w-full" />
            </div>

            <button type="submit" name="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">Order Now</button>
        </form>
    </div>

    <script>
        updateProductDetails();
    </script>

</body>
</html>
<?php