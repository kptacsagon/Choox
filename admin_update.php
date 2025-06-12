<?php

@include 'config.php';

$id = $_GET['edit'];
$select = mysqli_query($conn, "SELECT * FROM products WHERE id = $id");

if (isset($_POST['update_product'])) {

    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $product_price = mysqli_real_escape_string($conn, $_POST['product_price']);
    $product_image = $_FILES['product_image']['name'];
    $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
    $product_image_folder = 'uploaded_img/' . $product_image;

    if (empty($product_name) || empty($product_price)) {
        $message[] = 'Please fill out all required fields.';
    } else {
        if (!empty($product_image)) {
            $update = "UPDATE products SET name='$product_name', price='$product_price', image='$product_image' WHERE id = $id";
            move_uploaded_file($product_image_tmp_name, $product_image_folder);
        } else {
            $update = "UPDATE products SET name='$product_name', price='$product_price' WHERE id = $id";
        }

        $result = mysqli_query($conn, $update);

        if ($result) {
            header('location:admin_page.php');
        } else {
            $message[] = 'Could not update the product.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php
    if (isset($message)) {
        foreach ($message as $msg) {
            echo '<span class="message">' .$msg . '</span>';
        }
    }
    ?>

    <div class="container">

        <div class="admin-product-form-container">

            <?php while ($row = mysqli_fetch_assoc($select)) { ?>
                <form action="" method="post" enctype="multipart/form-data">
                    <h3>Update Product</h3>
                    <input type="text" name="product_name" value="<?php echo $row['name']; ?>" class="box">
                    <input type="number" name="product_price" value="<?php echo $row['price']; ?>" class="box" step="0.01">
                    <input type="file" name="product_image" class="box">
                    <input type="submit" name="update_product" value="Update Product" class="btn">
                    <a href="admin_page.php" class="btn">Go Back</a>
                </form>
            <?php } ?>

        </div>

    </div>

</body>

</html>
