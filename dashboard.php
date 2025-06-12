<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
include 'db.php';
$username = htmlspecialchars($_SESSION['username']);

// Total products
$totalItems = $conn->query("SELECT COUNT(*) AS total FROM products")
                  ->fetch_assoc()['total'] ?? 0;

// Total orders
$totalOrders = $conn->query("SELECT COUNT(*) AS total FROM orders")
                   ->fetch_assoc()['total'] ?? 0;

// Total customers
$totalCustomers = $conn->query("SELECT COUNT(*) AS total FROM customers")
                      ->fetch_assoc()['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Inventory Management Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen flex relative">
  <!-- Background image -->
  <div style="background: url('uploaded_img/dark_wood.jpg') no-repeat center center fixed; background-size: cover;" class="absolute top-0 left-0 w-full h-full -z-10"></div>

  <!-- Brown transparent overlay -->
  <div class="absolute top-0 left-0 w-full h-full bg-yellow-900 bg-opacity-50 -z-10"></div>

  <?php include 'sidebar.php'; ?>

  <!-- Rest of your content here -->




  <!-- Sidebar -->
  <?php include 'sidebar.php'; ?>

  <!-- Main Content -->
  <div class="flex-1 p-8 overflow-auto ml-64">
    <!-- Header -->
    <header class="flex justify-between items-center mb-8">
      <h2 class="text-3xl font-semibold text-gray-800">Dashboard Overview</h2>
      <div class="flex items-center space-x-6">
        <!-- Notification Bell -->
        <button class="relative focus:outline-none">
          <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 
                     .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
          </svg>
          <span class="absolute -top-1 -right-1 inline-block w-2 h-2 bg-red-600 rounded-full"></span>
        </button>
        <!-- Profile Dropdown -->
        <div class="relative">
          <button onclick="toggleDropdown()" class="flex items-center space-x-2 bg-gray-200 px-4 py-2 rounded hover:bg-gray-300 focus:outline-none">
            <span class="text-gray-700 font-medium"><?= $username ?></span>
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-40 bg-white rounded shadow-lg py-2 z-10">
            <a href="logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100"
               onclick="return confirm('Are you sure you want to logout?');">Logout</a>
          </div>
        </div>
      </div>
    </header>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-green-100 shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-600">Total Items</h3>
        <p class="mt-2 text-3xl font-semibold text-gray-800"><?= $totalItems ?></p>
      </div>
      <div class="bg-green-100 shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-600">Total Orders</h3>
        <p class="mt-2 text-3xl font-semibold text-gray-800"><?= $totalOrders ?></p>
      </div>
      <div class="bg-green-100 shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-600">Total Customers</h3>
        <p class="mt-2 text-3xl font-semibold text-gray-800"><?= $totalCustomers ?></p>
      </div>
    </div>

    <!-- Placeholder for charts / tables -->
  <section class="mt-12">
  <div class="bg-green-100 shadow-lg rounded-2xl p-8 hover:shadow-2xl transition duration-300">
    <h3 class="text-2xl font-semibold text-gray-800 mb-6 border-b pb-3">Inventory Trends</h3>
    <div class="flex flex-wrap justify-center gap-6">
      <img src="uploaded_img/bg2.jpg" alt="Inventory Trends Chart" class="rounded-lg shadow-md w-64 h-40 object-cover hover:scale-105 transition-transform duration-300">
      <img src="uploaded_img/bg.jpg" alt="Inventory Trends Chart" class="rounded-lg shadow-md w-64 h-40 object-cover hover:scale-105 transition-transform duration-300">
      <img src="uploaded_img/bg1.jpg" alt="Inventory Trends Chart" class="rounded-lg shadow-md w-64 h-40 object-cover hover:scale-105 transition-transform duration-300">
      <img src="uploaded_img/bg3.jpg" alt="Inventory Trends Chart" class="rounded-lg shadow-md w-64 h-40 object-cover hover:scale-105 transition-transform duration-300">
      <img src="uploaded_img/bg4.jpg" alt="Inventory Trends Chart" class="rounded-lg shadow-md w-64 h-40 object-cover hover:scale-105 transition-transform duration-300">
      <img src="uploaded_img/bg5.jpg" alt="Inventory Trends Chart" class="rounded-lg shadow-md w-64 h-40 object-cover hover:scale-105 transition-transform duration-300">
      <img src="uploaded_img/bg6.jpg" alt="Inventory Trends Chart" class="rounded-lg shadow-md w-64 h-40 object-cover hover:scale-105 transition-transform duration-300">
      <img src="uploaded_img/bg7.jpg" alt="Inventory Trends Chart" class="rounded-lg shadow-md w-64 h-40 object-cover hover:scale-105 transition-transform duration-300">
    </div>
  </div>
</section>


  </div>

  <script>
    function toggleDropdown() {
      document.getElementById('dropdownMenu').classList.toggle('hidden');
    }
    window.addEventListener('click', e => {
      if (!e.target.closest('.relative')) {
        document.getElementById('dropdownMenu').classList.add('hidden');
      }
    });
  </script>
</body>
</html>
