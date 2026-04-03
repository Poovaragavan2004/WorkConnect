<?php
require_once 'config.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

// Get all bookings
$query = "SELECT b.*, w.full_name as worker_name, w.profession, w.phone as worker_phone, w.rating 
          FROM bookings b 
          JOIN workers w ON b.worker_id = w.id 
          WHERE b.customer_id = $customer_id 
          ORDER BY b.booking_date DESC, b.booking_time DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>My Bookings - LocalWorks</title>
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
            <a href="customer_dashboard.php" class="text-gray-600 dark:text-gray-400">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">My Bookings</h1>
            <div class="w-10"></div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto p-4 space-y-4">
        
        <!-- Filter Tabs -->
        <div class="flex gap-2 overflow-x-auto pb-2">
            <button class="px-4 py-2 rounded-lg bg-primary text-white whitespace-nowrap">All</button>
            <button class="px-4 py-2 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700 whitespace-nowrap">Pending</button>
            <button class="px-4 py-2 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700 whitespace-nowrap">Accepted</button>
            <button class="px-4 py-2 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700 whitespace-nowrap">Completed</button>
        </div>

        <!-- Bookings List -->
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while($booking = mysqli_fetch_assoc($result)): ?>
            <div class="bg-white dark:bg-gray-900 rounded-xl p-4 border border-gray-200 dark:border-gray-800">
                
                <!-- Status Badge -->
                <div class="flex justify-between items-start mb-3">
                    <span class="px-3 py-1 rounded-full text-xs font-medium
                        <?php 
                            if($booking['status'] == 'pending') echo 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400';
                            elseif($booking['status'] == 'accepted') echo 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
                            elseif($booking['status'] == 'completed') echo 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400';
                            elseif($booking['status'] == 'declined') echo 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
                            else echo 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400';
                        ?>">
                        <?php echo ucfirst($booking['status']); ?>
                    </span>
                    <span class="text-sm text-gray-600 dark:text-gray-400">
                        Booking #<?php echo $booking['id']; ?>
                    </span>
                </div>

                <!-- Worker Info -->
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-12 h-12 rounded-full bg-gray-300 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-gray-600 dark:text-gray-400">person</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-900 dark:text-white"><?php echo htmlspecialchars($booking['worker_name']); ?></h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400"><?php echo htmlspecialchars($booking['profession']); ?></p>
                        <div class="flex items-center gap-1 mt-1">
                            <span class="material-symbols-outlined text-yellow-400 text-xs" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="text-xs text-gray-600 dark:text-gray-400"><?php echo $booking['rating']; ?></span>
                        </div>
                    </div>
                </div>

                <!-- Booking Details -->
                <div class="space-y-2 text-sm">
                    <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                        <span class="material-symbols-outlined text-lg">event</span>
                        <span><?php echo date('F d, Y', strtotime($booking['booking_date'])); ?> at <?php echo date('g:i A', strtotime($booking['booking_time'])); ?></span>
                    </div>
                    
                    <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                        <span class="material-symbols-outlined text-lg">location_on</span>
                        <span><?php echo htmlspecialchars($booking['service_address']); ?></span>
                    </div>
                    
                    <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                        <span class="material-symbols-outlined text-lg">work</span>
                        <span><?php echo htmlspecialchars($booking['service_type']); ?></span>
                    </div>
                    
                    <?php if ($booking['job_details']): ?>
                    <div class="mt-2 p-3 bg-gray-100 dark:bg-gray-800 rounded-lg">
                        <p class="text-xs text-gray-600 dark:text-gray-400 font-medium mb-1">Job Details:</p>
                        <p class="text-sm text-gray-900 dark:text-white"><?php echo htmlspecialchars($booking['job_details']); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <div class="flex justify-between items-center pt-2 border-t border-gray-200 dark:border-gray-800">
                        <span class="text-gray-600 dark:text-gray-400">Estimated Cost:</span>
                        <span class="text-lg font-bold text-primary">$<?php echo number_format($booking['estimated_earnings'], 2); ?></span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-2 mt-4">
                    <?php if ($booking['status'] == 'accepted' || $booking['status'] == 'completed'): ?>
                    <a href="tel:<?php echo $booking['worker_phone']; ?>" class="flex-1 text-center px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-800">
                        <span class="material-symbols-outlined text-sm align-middle">call</span> Call Worker
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($booking['status'] == 'pending'): ?>
                    <button class="flex-1 px-4 py-2 rounded-lg bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900/50">
                        Cancel Request
                    </button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="text-center py-12">
                <span class="material-symbols-outlined text-6xl text-gray-400 mb-4">event_busy</span>
                <p class="text-gray-600 dark:text-gray-400 mb-4">No bookings yet</p>
                <a href="search_workers.php" class="inline-block px-6 py-3 rounded-lg bg-primary text-white hover:bg-primary/90">
                    Find a Worker
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>