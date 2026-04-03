<?php
require_once 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$query = "SELECT b.*, 
          c.full_name as customer_name, c.email as customer_email,
          w.full_name as worker_name, w.profession
          FROM bookings b
          JOIN customers c ON b.customer_id = c.id
          JOIN workers w ON b.worker_id = w.id
          ORDER BY b.booking_date DESC, b.booking_time DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Bookings - Admin</title>
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
            <a href="admin_dashboard.php" class="text-gray-600 dark:text-gray-400">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">All Bookings</h1>
            <div class="w-10"></div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto p-4 space-y-6">
        
        <!-- Bookings List -->
        <div class="space-y-4">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while($booking = mysqli_fetch_assoc($result)): ?>
                <div class="bg-white dark:bg-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-800">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                <?php echo htmlspecialchars($booking['service_type']); ?>
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Booking #<?php echo $booking['id']; ?></p>
                        </div>
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
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Customer Info -->
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">CUSTOMER</p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-700 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-gray-600 dark:text-gray-400">person</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white"><?php echo htmlspecialchars($booking['customer_name']); ?></p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400"><?php echo htmlspecialchars($booking['customer_email']); ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Worker Info -->
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">WORKER</p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-700 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-gray-600 dark:text-gray-400">engineering</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white"><?php echo htmlspecialchars($booking['worker_name']); ?></p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400"><?php echo htmlspecialchars($booking['profession']); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-800">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 mb-1">Date & Time</p>
                                <p class="text-gray-900 dark:text-white font-medium">
                                    <?php echo date('M d, Y - g:i A', strtotime($booking['booking_date'] . ' ' . $booking['booking_time'])); ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 mb-1">Location</p>
                                <p class="text-gray-900 dark:text-white font-medium truncate">
                                    <?php echo htmlspecialchars($booking['service_address']); ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 mb-1">Estimated Cost</p>
                                <p class="text-primary font-bold text-lg">
                                    $<?php echo number_format($booking['estimated_earnings'], 2); ?>
                                </p>
                            </div>
                        </div>

                        <?php if ($booking['job_details']): ?>
                        <div class="mt-4 p-3 bg-gray-100 dark:bg-gray-800 rounded-lg">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Job Details:</p>
                            <p class="text-sm text-gray-900 dark:text-white"><?php echo htmlspecialchars($booking['job_details']); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="text-center py-12 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800">
                    <span class="material-symbols-outlined text-6xl text-gray-400 mb-4">event_busy</span>
                    <p class="text-gray-600 dark:text-gray-400">No bookings found</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>