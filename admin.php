<?php include 'db.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - View Orders</title>
</head>
<body>
    <h2>All Orders</h2>

    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Item</th>
            <th>Quantity</th>
            <th>Date</th>
        </tr>

        <?php
        $result = $conn->query("SELECT * FROM orders ORDER BY id DESC");

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['customer_name']}</td>
                <td>{$row['item']}</td>
                <td>{$row['quantity']}</td>
                <td>{$row['order_date']}</td>
            </tr>";
        }

        $conn->close();
        ?>
    </table>
</body>
</html>
