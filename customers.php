<?php
$mysqli = new mysqli("localhost", "root", "", "a_winventory_system");
if ($mysqli->connect_error) die("Connection failed: " . $mysqli->connect_error);

$message = "";

// ADD
if (isset($_POST['add'])) {
    $stmt = $mysqli->prepare("INSERT INTO customers (first_name, last_name, tel, email) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $_POST['first_name'], $_POST['last_name'], $_POST['tel'], $_POST['email']);
    $stmt->execute();
    $message = "New customer added";
}

// EDIT
if (isset($_POST['edit'])) {
    $stmt = $mysqli->prepare("UPDATE customers SET first_name=?, last_name=?, tel=?, email=? WHERE id=?");
    $stmt->bind_param("ssssi", $_POST['first_name'], $_POST['last_name'], $_POST['tel'], $_POST['email'], $_POST['id']);
    $stmt->execute();
    $message = "Customer updated";
}

// REMOVE
if (isset($_POST['remove'])) {
    $stmt = $mysqli->prepare("DELETE FROM customers WHERE id=?");
    $stmt->bind_param("i", $_POST['id']);
    $stmt->execute();
    $message = "Customer removed";
}

$result = $mysqli->query("SELECT * FROM customers ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">Customers</h2>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>

    <div class="row">
        <!-- Table -->
        <div class="col-md-8">
            <table class="table table-bordered bg-white">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Tel</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['first_name']) ?></td>
                        <td><?= htmlspecialchars($row['last_name']) ?></td>
                        <td><?= htmlspecialchars($row['tel']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Form -->
        <div class="col-md-4">
            <form method="post" class="card card-body shadow-sm">
                <h5 class="mb-3">Manage</h5>
                <input type="number" name="id" class="form-control mb-2" placeholder="ID (for edit/remove)">
                <input type="text" name="first_name" class="form-control mb-2" placeholder="First Name" required>
                <input type="text" name="last_name" class="form-control mb-2" placeholder="Last Name" required>
                <input type="text" name="tel" class="form-control mb-2" placeholder="Telephone" required>
                <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>

                <div class="d-flex gap-2">
                    <button name="add" class="btn btn-success w-100">Add</button>
                    <button name="edit" class="btn btn-warning w-100">Edit</button>
                    <button name="remove" class="btn btn-danger w-100">Remove</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div style="position: fixed; bottom: 20px; right: 20px;">
  <a href="dashboard.php" class="bg-blue-600 text-blue px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition text-decoration-none">
    ← Return
  </a>
</div>
<a href="dashboard.php" style="
    position: absolute;
    top: 10px;
    right: 140px;
    background-color: #4b5563;
    color: white;
    padding: 2px 10px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    text-decoration: none;
    transition: background-color 0.3s ease;
" onmouseover="this.style.backgroundColor='#374151'" onmouseout="this.style.backgroundColor='#4b5563'">
    Return
</a>
</body>
</html>
