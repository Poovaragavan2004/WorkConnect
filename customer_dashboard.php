<?php
require_once 'config.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];
$customer_name = $_SESSION['customer_name'];

// Get customer info
$query = "SELECT * FROM customers WHERE id = $customer_id";
$result = mysqli_query($conn, $query);
$customer = mysqli_fetch_assoc($result);

// Get recent bookings
$bookings_query = "SELECT b.*, w.full_name as worker_name, w.profession, w.profile_photo 
                   FROM bookings b 
                   JOIN workers w ON b.worker_id = w.id 
                   WHERE b.customer_id = $customer_id 
                   ORDER BY b.created_at DESC 
                   LIMIT 3";
$bookings_result = mysqli_query($conn, $bookings_query);
?>
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Dashboard - LocalWorks</title>
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
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
            <div class="flex items-center gap-4">
                <a href="search_workers.php" class="text-gray-600 dark:text-gray-400 hover:text-primary">
                    <span class="material-symbols-outlined">search</span>
                </a>
                <a href="logout.php" class="text-gray-600 dark:text-gray-400 hover:text-red-500">
                    <span class="material-symbols-outlined">logout</span>
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto p-4 space-y-6">
        
        <!-- Welcome Card -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl p-6 text-white">
            <h2 class="text-2xl font-bold">Welcome back, <?php echo htmlspecialchars($customer_name); ?>!</h2>
            <p class="mt-2 text-blue-100">Find local workers for your next project</p>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-2 gap-4">
            <a href="search_workers.php" class="bg-white dark:bg-gray-900 rounded-xl p-6 text-center hover:shadow-lg transition-shadow border border-gray-200 dark:border-gray-800">
                <span class="material-symbols-outlined text-4xl text-primary">search</span>
                <h3 class="mt-2 font-semibold text-gray-900 dark:text-white">Find Workers</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Search by category</p>
            </a>
            
            <a href="customer_bookings.php" class="bg-white dark:bg-gray-900 rounded-xl p-6 text-center hover:shadow-lg transition-shadow border border-gray-200 dark:border-gray-800">
                <span class="material-symbols-outlined text-4xl text-primary">calendar_month</span>
                <h3 class="mt-2 font-semibold text-gray-900 dark:text-white">My Bookings</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">View all bookings</p>
            </a>
        </div>

        <!-- Recent Bookings -->
        <div class="bg-white dark:bg-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-800">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Recent Bookings</h3>
                <a href="customer_bookings.php" class="text-sm text-primary hover:underline">View All</a>
            </div>
            
            <div class="space-y-4">
                <?php if (mysqli_num_rows($bookings_result) > 0): ?>
                    <?php while($booking = mysqli_fetch_assoc($bookings_result)): ?>
                    <div class="border border-gray-200 dark:border-gray-800 rounded-lg p-4">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-full bg-gray-300 dark:bg-gray-700 flex items-center justify-center">
                                <span class="material-symbols-outlined text-gray-600 dark:text-gray-400">person</span>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900 dark:text-white"><?php echo htmlspecialchars($booking['worker_name']); ?></h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400"><?php echo htmlspecialchars($booking['profession']); ?></p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    <?php echo date('M d, Y - g:i A', strtotime($booking['booking_date'] . ' ' . $booking['booking_time'])); ?>
                                </p>
                                <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-medium 
                                    <?php 
                                        if($booking['status'] == 'pending') echo 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400';
                                        elseif($booking['status'] == 'accepted') echo 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
                                        elseif($booking['status'] == 'completed') echo 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400';
                                        else echo 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
                                    ?>">
                                    <?php echo ucfirst($booking['status']); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <span class="material-symbols-outlined text-5xl mb-2">event_busy</span>
                        <p>No bookings yet</p>
                        <a href="search_workers.php" class="text-primary hover:underline">Find a worker</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Profile Section -->
        <div class="bg-white dark:bg-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-800">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">My Profile</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Email:</span>
                    <span class="text-gray-900 dark:text-white"><?php echo htmlspecialchars($customer['email']); ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Phone:</span>
                    <span class="text-gray-900 dark:text-white"><?php echo htmlspecialchars($customer['phone']); ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Address:</span>
                    <span class="text-gray-900 dark:text-white text-right"><?php echo htmlspecialchars($customer['address']); ?></span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>