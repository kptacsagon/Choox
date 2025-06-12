<?php
$mysqli = new mysqli("localhost", "root", "", "a_winventory_system");
if ($mysqli->connect_error) die("Connection failed: " . $mysqli->connect_error);

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
            $dateCondition = "1";
    }
}

$query = "SELECT * FROM orders WHERE $dateCondition ORDER BY created_at DESC";
$result = $mysqli->query($query);

// Set CSV headers
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=sales_export.csv');

// Open output stream
$output = fopen('php://output', 'w');
fputcsv($output, ['Order ID', 'Customer', 'Products', 'Amount (₱)', 'Status', 'Order Date']);

while ($row = $result->fetch_assoc()) {
    // Format created_at to 'Y-m-d' (or use full datetime if you prefer)
    $formattedDate = date('Y-m-d', strtotime($row['created_at']));

    fputcsv($output, [
        $row['id'],
        $row['customer_name'],
        $row['products'],
        $row['amount'],
        $row['status'],
        $formattedDate
    ]);
}


fclose($output);
exit;
?>
