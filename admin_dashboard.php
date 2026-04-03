<?php
require_once 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Get statistics
$total_workers = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM workers"));
$pending_workers = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM workers WHERE status = 'pending'"));
$approved_workers = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM workers WHERE status = 'approved'"));
$total_customers = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM customers"));
$total_bookings = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM bookings"));
$pending_bookings = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM bookings WHERE status = 'pending'"));
?>
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Admin Dashboard - LocalWorks</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#136dec",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101822",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    }
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="font-display bg-background-light dark:bg-background-dark">
    
    <!-- Top Navigation -->
    <div class="sticky top-0 z-10 bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-sm border-b border-gray-200 dark:border-gray-800">
        <div class="flex items-center justify-between p-4">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-600 text-white">
                    <span class="material-symbols-outlined">admin_panel_settings</span>
                </div>
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">Admin Dashboard</h1>
            </div>
            <a href="logout.php" class="text-gray-600 dark:text-gray-400 hover:text-red-500">
                <span class="material-symbols-outlined">logout</span>
            </a>
        </div>
    </div>

    <div class="max-w-6xl mx-auto p-4 space-y-6">
        
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Total Workers -->
            <div class="bg-white dark:bg-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Workers</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2"><?php echo $total_workers; ?></p>
                    </div>
                    <div class="h-12 w-12 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">engineering</span>
                    </div>
                </div>
            </div>
            
            <!-- Pending Approvals -->
            <div class="bg-white dark:bg-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Pending Approvals</p>
                        <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400 mt-2"><?php echo $pending_workers; ?></p>
                    </div>
                    <div class="h-12 w-12 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center">
                        <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400">pending</span>
                    </div>
                </div>
            </div>
            
            <!-- Total Customers -->
            <div class="bg-white dark:bg-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Customers</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2"><?php echo $total_customers; ?></p>
                    </div>
                    <div class="h-12 w-12 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                        <span class="material-symbols-outlined text-green-600 dark:text-green-400">group</span>
                    </div>
                </div>
            </div>
            
            <!-- Total Bookings -->
            <div class="bg-white dark:bg-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Bookings</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2"><?php echo $total_bookings; ?></p>
                    </div>
                    <div class="h-12 w-12 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                        <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">calendar_month</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            <a href="admin_workers.php" class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl p-6 text-white hover:shadow-lg transition-shadow">
                <span class="material-symbols-outlined text-4xl mb-3">engineering</span>
                <h3 class="text-xl font-bold">Manage Workers</h3>
                <p class="text-sm text-blue-100 mt-1">Approve or suspend workers</p>
                <?php if ($pending_workers > 0): ?>
                <div class="mt-3 inline-block px-3 py-1 rounded-full bg-yellow-400 text-yellow-900 text-xs font-bold">
                    <?php echo $pending_workers; ?> Pending
                </div>
                <?php endif; ?>
            </a>
            
            <a href="admin_customers.php" class="bg-gradient-to-br from-green-600 to-green-800 rounded-xl p-6 text-white hover:shadow-lg transition-shadow">
                <span class="material-symbols-outlined text-4xl mb-3">group</span>
                <h3 class="text-xl font-bold">View Customers</h3>
                <p class="text-sm text-green-100 mt-1">Customer list and details</p>
            </a>
            
            <a href="admin_bookings.php" class="bg-gradient-to-br from-purple-600 to-purple-800 rounded-xl p-6 text-white hover:shadow-lg transition-shadow">
                <span class="material-symbols-outlined text-4xl mb-3">calendar_month</span>
                <h3 class="text-xl font-bold">View Bookings</h3>
                <p class="text-sm text-purple-100 mt-1">All booking transactions</p>
            </a>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white dark:bg-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-800">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Platform Overview</h2>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-gray-100 dark:bg-gray-800 rounded-lg">
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400"><?php echo $approved_workers; ?></p>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Approved Workers</p>
                </div>
                <div class="text-center p-4 bg-gray-100 dark:bg-gray-800 rounded-lg">
                    <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400"><?php echo $pending_bookings; ?></p>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Pending Bookings</p>
                </div>
                <div class="text-center p-4 bg-gray-100 dark:bg-gray-800 rounded-lg">
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400"><?php echo number_format(($total_bookings > 0 ? ($total_bookings - $pending_bookings) / $total_bookings * 100 : 0), 0); ?>%</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Booking Success Rate</p>
                </div>
                <div class="text-center p-4 bg-gray-100 dark:bg-gray-800 rounded-lg">
                    <p class="text-2xl font-bold text-purple-600 dark:text-purple-400"><?php echo $total_workers + $total_customers; ?></p>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Total Users</p>
                </div>
            </div>
        </div>

        <!-- System Info -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">info</span>
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white">Admin Panel v1.0</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">LocalWorks Management System - You have full control over workers, customers, and bookings.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>