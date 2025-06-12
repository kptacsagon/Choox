<!-- sidebar.php -->
<aside class="w-64 bg-green-100 shadow-lg flex flex-col fixed h-screen">
  <div class="p-6 flex items-center justify-between border-b border-gray-200">
    <h1 class="text-2xl font-semibold text-gray-800">My Inventory</h1>
    <a href="logout.php" title="Logout">
      <svg class="w-6 h-6 text-gray-700 hover:text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7" />
      </svg>
    </a>
  </div>

  <nav class="mt-6 flex-1">
    <a href="dashboard.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-200">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18M3 6h18M3 18h18"/>
      </svg>
      <span class="ml-3">Dashboard</span>
    </a>

    <a href="inventory.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-200">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6M5 21h14a2 2 0 002-2v-8H3v8a2 2 0 002 2z"/>
      </svg>
      <span class="ml-3">Inventory</span>
    </a>

    <a href="orders.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-200">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18"/>
      </svg>
      <span class="ml-3">Orders</span>
    </a>

    <a href="sales.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-200">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18"/>
      </svg>
      <span class="ml-3">Sales</span>
    </a>

    <!-- Customers Page -->
    <a href="customers.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-200">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20h6M4 20h5v-2a4 4 0 00-4-4H4a4 4 0 00-4 4v2h4zM16 4a4 4 0 11-8 0 4 4 0 018 0z"/>
      </svg>
      <span class="ml-3">Customers</span>
    </a>

  
  </nav>


</aside>
