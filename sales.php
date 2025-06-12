<?php
$mysqli = new mysqli("localhost", "root", "", "a_winventory_system");
if ($mysqli->connect_error) die("Connection failed: " . $mysqli->connect_error);

// Date filter handling
$filter = $_GET['filter'] ?? 'today';
$startDate = $_GET['start_date'] ?? '';
$endDate = $_GET['end_date'] ?? '';

if ($filter === 'range' && $startDate && $endDate) {
    $dateCondition = "DATE(created_at) BETWEEN '$startDate' AND '$endDate'";
} else {
    switch ($filter) {
        case 'today':
            $dateCondition = "DATE(created_at) = CURDATE()";
            break;
        case 'month':
            $dateCondition = "MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())";
            break;
        case 'year':
            $dateCondition = "YEAR(created_at) = YEAR(CURDATE())";
            break;
        default:
            $dateCondition = "1"; // Show all
    }
}

// Get total sales
$query = "SELECT SUM(amount) AS total_sales FROM orders WHERE $dateCondition";
$result = $mysqli->query($query);
$row = $result->fetch_assoc();
$totalSales = $row['total_sales'] ?? 0;

// Get order list
$orderQuery = "SELECT * FROM orders WHERE $dateCondition ORDER BY created_at DESC";
$orderResult = $mysqli->query($orderQuery);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sales Summary</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Sales Summary</h2>
        <a href="dashboard.php" class="btn btn-secondary">Return</a>
    </div>

    <!-- Filter Form -->
    <form method="GET" class="mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Filter</label>
                <select name="filter" class="form-select" onchange="this.form.submit()">
                    <option value="today" <?= $filter === 'today' ? 'selected' : '' ?>>Today's Sales</option>
                    <option value="month" <?= $filter === 'month' ? 'selected' : '' ?>>This Month's Sales</option>
                    <option value="year" <?= $filter === 'year' ? 'selected' : '' ?>>This Year's Sales</option>
                    <option value="range" <?= $filter === 'range' ? 'selected' : '' ?>>Custom Range</option>
                </select>
            </div>
            <?php if ($filter === 'range'): ?>
                <div class="col-md-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" value="<?= htmlspecialchars($startDate) ?>" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" value="<?= htmlspecialchars($endDate) ?>" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">Apply Filter</button>
                </div>
            <?php endif; ?>
        </div>
    </form>

    <!-- Sales Summary -->
    <div class="card p-4 shadow-sm mb-4">
        <h4>Total Sales (₱)</h4>
        <h1 class="display-4 text-success">₱<?= number_format($totalSales, 2) ?></h1>
        <a href="export_sales.php?filter=<?= $filter ?>&start_date=<?= $startDate ?>&end_date=<?= $endDate ?>" class="btn btn-outline-success mt-3">Export to CSV</a>
    </div>

    <!-- Orders Table -->
    <table class="table table-bordered bg-white">
        <thead class="table-light">
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Products</th>
                <th>Amount (₱)</th>
                <th>Status</th>
                <th>Order Date</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($orderResult->num_rows > 0): ?>
            <?php while ($order = $orderResult->fetch_assoc()): ?>
                <tr>
                    <td><?= $order['id'] ?></td>
                    <td><?= htmlspecialchars($order['customer_name']) ?></td>
                    <td><?= htmlspecialchars($order['products']) ?></td>
                    <td>₱<?= number_format($order['amount'], 2) ?></td>
                    <td><?= htmlspecialchars($order['status']) ?></td>
                    <td><?= $order['created_at'] ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6" class="text-center">No orders found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
