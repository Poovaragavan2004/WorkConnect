<?php
require_once 'config.php';

if (!isset($_SESSION['worker_id'])) {
    header("Location: worker_login.php");
    exit();
}

$worker_id = $_SESSION['worker_id'];

// Get worker info
$query = "SELECT * FROM workers WHERE id = $worker_id";
$result = mysqli_query($conn, $query);
$worker = mysqli_fetch_assoc($result);

// Handle availability toggle
if (isset($_POST['toggle_availability'])) {
    $new_status = $worker['is_available'] ? 0 : 1;
    $update = "UPDATE workers SET is_available = $new_status WHERE id = $worker_id";
    mysqli_query($conn, $update);
    header("Location: worker_dashboard.php");
    exit();
}

// Get pending requests
$pending_query = "SELECT b.*, c.full_name as customer_name, c.phone as customer_phone, c.address as customer_address 
                  FROM bookings b 
                  JOIN customers c ON b.customer_id = c.id 
                  WHERE b.worker_id = $worker_id AND b.status = 'pending' 
                  ORDER BY b.booking_date ASC, b.booking_time ASC";
$pending_result = mysqli_query($conn, $pending_query);

// Get accepted bookings
$accepted_query = "SELECT b.*, c.full_name as customer_name, c.phone as customer_phone 
                   FROM bookings b 
                   JOIN customers c ON b.customer_id = c.id 
                   WHERE b.worker_id = $worker_id AND b.status = 'accepted' 
                   ORDER BY b.booking_date ASC, b.booking_time ASC";
$accepted_result = mysqli_query($conn, $accepted_query);
?>
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Worker Dashboard - LocalWorks</title>
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
<body class="font-display bg-background-light dark:bg-background-dark pb-20">
    
    <!-- Top Navigation -->
    <div class="sticky top-0 z-10 bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-sm border-b border-gray-200 dark:border-gray-800">
        <div class="flex items-center justify-between p-4">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
            <div class="flex items-center gap-4">
                <a href="worker_profile_edit.php" class="text-gray-600 dark:text-gray-400 hover:text-primary">
                    <span class="material-symbols-outlined">settings</span>
                </a>
                <a href="logout.php" class="text-gray-600 dark:text-gray-400 hover:text-red-500">
                    <span class="material-symbols-outlined">logout</span>
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto p-4 space-y-6">
        
        <!-- Availability Toggle -->
        <div class="bg-white dark:bg-gray-900 rounded-xl p-5 border border-gray-200 dark:border-gray-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-900 dark:text-white font-bold">
                        You are currently <?php echo $worker['is_available'] ? 'Available' : 'Unavailable'; ?>
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        <?php echo $worker['is_available'] ? 'You are visible to customers' : 'You are hidden from customers'; ?>
                    </p>
                </div>
                <form method="POST">
                    <button type="submit" name="toggle_availability" class="relative flex h-8 w-14 items-center rounded-full p-0.5 <?php echo $worker['is_available'] ? 'bg-primary' : 'bg-gray-300 dark:bg-gray-700'; ?>">
                        <div class="h-7 w-7 rounded-full bg-white transition-transform <?php echo $worker['is_available'] ? 'translate-x-6' : ''; ?>"></div>
                    </button>
                </form>
            </div>
        </div>

        <!-- Profile Summary -->
        <a href="worker_profile_edit.php" class="block bg-white dark:bg-gray-900 rounded-xl p-4 border border-gray-200 dark:border-gray-800 hover:border-primary transition-colors">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-gray-300 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-2xl text-gray-600 dark:text-gray-400">person</span>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-lg text-gray-900 dark:text-white"><?php echo htmlspecialchars($worker['full_name']); ?></h3>
                    <p class="text-gray-600 dark:text-gray-400"><?php echo htmlspecialchars($worker['profession']); ?></p>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="material-symbols-outlined text-yellow-400 text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="text-sm text-gray-900 dark:text-white"><?php echo $worker['rating']; ?> (<?php echo $worker['total_reviews']; ?> reviews)</span>
                    </div>
                </div>
                <span class="material-symbols-outlined text-gray-400">chevron_right</span>
            </div>
        </a>

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white dark:bg-gray-900 rounded-xl p-4 border border-gray-200 dark:border-gray-800 text-center">
                <span class="material-symbols-outlined text-3xl text-yellow-500">pending</span>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2"><?php echo mysqli_num_rows($pending_result); ?></p>
                <p class="text-sm text-gray-600 dark:text-gray-400">Pending</p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-xl p-4 border border-gray-200 dark:border-gray-800 text-center">
                <span class="material-symbols-outlined text-3xl text-green-500">check_circle</span>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2"><?php echo mysqli_num_rows($accepted_result); ?></p>
                <p class="text-sm text-gray-600 dark:text-gray-400">Upcoming</p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-xl p-4 border border-gray-200 dark:border-gray-800 text-center">
                <span class="material-symbols-outlined text-3xl text-primary">star</span>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2"><?php echo $worker['rating']; ?></p>
                <p class="text-sm text-gray-600 dark:text-gray-400">Rating</p>
            </div>
        </div>

        <!-- Tabs -->
        <div class="flex gap-2 border-b border-gray-200 dark:border-gray-800">
            <button class="px-4 py-3 border-b-2 border-primary text-primary font-semibold">
                New Requests (<?php echo mysqli_num_rows($pending_result); ?>)
            </button>
            <button onclick="window.location.href='worker_upcoming_jobs.php'" class="px-4 py-3 text-gray-600 dark:text-gray-400 hover:text-primary">
                Upcoming Jobs (<?php echo mysqli_num_rows($accepted_result); ?>)
            </button>
        </div>

        <!-- Pending Requests -->
        <div class="space-y-4">
            <?php if (mysqli_num_rows($pending_result) > 0): ?>
                <?php mysqli_data_seek($pending_result, 0); ?>
                <?php while($booking = mysqli_fetch_assoc($pending_result)): ?>
                <div class="bg-white dark:bg-gray-900 rounded-xl p-4 border border-gray-200 dark:border-gray-800">
                    <div class="flex justify-between items-start mb-3">
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                            New Request
                        </span>
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            Booking #<?php echo $booking['id']; ?>
                        </span>
                    </div>
                    
                    <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-2">
                        <?php echo htmlspecialchars($booking['service_type']); ?>
                    </h3>
                    
                    <div class="space-y-2 text-sm mb-4">
                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                            <span class="material-symbols-outlined text-lg">person</span>
                            <span><?php echo htmlspecialchars($booking['customer_name']); ?></span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                            <span class="material-symbols-outlined text-lg">event</span>
                            <span><?php echo date('M d, Y - g:i A', strtotime($booking['booking_date'] . ' ' . $booking['booking_time'])); ?></span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                            <span class="material-symbols-outlined text-lg">location_on</span>
                            <span><?php echo htmlspecialchars($booking['service_address']); ?></span>
                        </div>
                        
                        <?php if ($booking['job_details']): ?>
                        <div class="mt-2 p-3 bg-gray-100 dark:bg-gray-800 rounded-lg">
                            <p class="text-xs text-gray-600 dark:text-gray-400 font-medium mb-1">Job Details:</p>
                            <p class="text-sm text-gray-900 dark:text-white"><?php echo htmlspecialchars($booking['job_details']); ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <div class="flex justify-between items-center pt-2 border-t border-gray-200 dark:border-gray-800">
                            <span class="text-gray-600 dark:text-gray-400">Estimated Payout:</span>
                            <span class="text-lg font-bold text-primary">$<?php echo number_format($booking['estimated_earnings'], 2); ?></span>
                        </div>
                    </div>
                    
                    <div class="flex gap-3">
                        <form method="POST" action="worker_respond_booking.php" class="flex-1">
                            <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>"/>
                            <input type="hidden" name="action" value="decline"/>
                            <button type="submit" class="w-full px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-800 text-gray-900 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-700">
                                Decline
                            </button>
                        </form>
                        <form method="POST" action="worker_respond_booking.php" class="flex-1">
                            <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>"/>
                            <input type="hidden" name="action" value="accept"/>
                            <button type="submit" class="w-full px-4 py-2 rounded-lg bg-primary text-white hover:bg-primary/90">
                                Accept Job
                            </button>
                        </form>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="text-center py-12 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800">
                    <span class="material-symbols-outlined text-6xl text-gray-400 mb-4">inbox</span>
                    <p class="text-gray-600 dark:text-gray-400">No pending requests</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>