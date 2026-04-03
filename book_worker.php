<?php
require_once 'config.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_login.php");
    exit();
}

$worker_id = isset($_GET['worker_id']) ? (int)$_GET['worker_id'] : 0;
$customer_id = $_SESSION['customer_id'];

// Get worker info
$query = "SELECT * FROM workers WHERE id = $worker_id AND status = 'approved'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: search_workers.php");
    exit();
}

$worker = mysqli_fetch_assoc($result);

// Get customer address
$customer_query = "SELECT address FROM customers WHERE id = $customer_id";
$customer_result = mysqli_query($conn, $customer_query);
$customer = mysqli_fetch_assoc($customer_result);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $service_type = mysqli_real_escape_string($conn, $_POST['service_type']);
    $booking_date = mysqli_real_escape_string($conn, $_POST['booking_date']);
    $booking_time = mysqli_real_escape_string($conn, $_POST['booking_time']);
    $service_address = mysqli_real_escape_string($conn, $_POST['service_address']);
    $job_details = mysqli_real_escape_string($conn, $_POST['job_details']);
    $estimated_earnings = $worker['hourly_rate'] * 2; // Estimate 2 hours
    
    $insert_query = "INSERT INTO bookings (customer_id, worker_id, service_type, booking_date, booking_time, service_address, job_details, estimated_earnings, status) 
                     VALUES ($customer_id, $worker_id, '$service_type', '$booking_date', '$booking_time', '$service_address', '$job_details', $estimated_earnings, 'pending')";
    
    if (mysqli_query($conn, $insert_query)) {
        $success = "Booking request sent successfully! Waiting for worker confirmation.";
        header("refresh:2;url=customer_bookings.php");
    } else {
        $error = "Failed to create booking. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Book Worker - LocalWorks</title>
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
<body class="font-display bg-background-light dark:bg-background-dark pb-24">
    
    <!-- Top Navigation -->
    <div class="sticky top-0 z-10 bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-sm border-b border-gray-200 dark:border-gray-800">
        <div class="flex items-center justify-between p-4">
            <a href="search_workers.php" class="text-gray-600 dark:text-gray-400">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Request Booking</h1>
            <div class="w-10"></div>
        </div>
    </div>

    <div class="max-w-2xl mx-auto p-4 space-y-6">
        
        <!-- Worker Profile Header -->
        <div class="bg-white dark:bg-gray-900 rounded-xl p-4 border border-gray-200 dark:border-gray-800">
            <div class="flex items-center gap-4">
                <div class="w-20 h-20 rounded-full bg-gray-300 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-3xl text-gray-600 dark:text-gray-400">person</span>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white"><?php echo htmlspecialchars($worker['full_name']); ?></h2>
                    <p class="text-gray-600 dark:text-gray-400"><?php echo htmlspecialchars($worker['profession']); ?></p>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="material-symbols-outlined text-yellow-400 text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white"><?php echo $worker['rating']; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages -->
        <?php if ($error): ?>
        <div class="bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg">
            <?php echo $error; ?>
        </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg">
            <?php echo $success; ?>
        </div>
        <?php endif; ?>

        <!-- Booking Form -->
        <form method="POST" class="space-y-4">
            
            <!-- Service Type -->
            <div class="bg-white dark:bg-gray-900 rounded-xl p-4 border border-gray-200 dark:border-gray-800">
                <label class="block">
                    <span class="text-gray-900 dark:text-white font-medium mb-2 block">Service Type</span>
                    <select name="service_type" required class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                        <option value="">Select a service</option>
                        <option value="Plumbing Repair">Plumbing Repair</option>
                        <option value="Electrical Work">Electrical Work</option>
                        <option value="Landscaping">Landscaping</option>
                        <option value="Carpentry">Carpentry</option>
                        <option value="General Maintenance">General Maintenance</option>
                        <option value="Other">Other</option>
                    </select>
                </label>
            </div>

            <!-- Date and Time -->
            <div class="bg-white dark:bg-gray-900 rounded-xl p-4 border border-gray-200 dark:border-gray-800 space-y-4">
                <label class="block">
                    <span class="text-gray-900 dark:text-white font-medium mb-2 block">Date</span>
                    <input 
                        type="date" 
                        name="booking_date"
                        min="<?php echo date('Y-m-d'); ?>"
                        required 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                    />
                </label>
                
                <label class="block">
                    <span class="text-gray-900 dark:text-white font-medium mb-2 block">Time</span>
                    <input 
                        type="time" 
                        name="booking_time"
                        required 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                    />
                </label>
            </div>

            <!-- Service Address -->
            <div class="bg-white dark:bg-gray-900 rounded-xl p-4 border border-gray-200 dark:border-gray-800">
                <label class="block">
                    <span class="text-gray-900 dark:text-white font-medium mb-2 block">Service Address</span>
                    <textarea 
                        name="service_address"
                        rows="3"
                        required 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                    ><?php echo htmlspecialchars($customer['address']); ?></textarea>
                </label>
            </div>

            <!-- Job Details -->
            <div class="bg-white dark:bg-gray-900 rounded-xl p-4 border border-gray-200 dark:border-gray-800">
                <label class="block">
                    <span class="text-gray-900 dark:text-white font-medium mb-2 block">Job Details</span>
                    <textarea 
                        name="job_details"
                        rows="4"
                        placeholder="Describe the issue or work needed..."
                        required 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                    ></textarea>
                </label>
            </div>

            <!-- Estimated Cost -->
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 border border-blue-200 dark:border-blue-800">
                <div class="flex justify-between items-center">
                    <span class="text-gray-900 dark:text-white font-medium">Estimated Cost:</span>
                    <span class="text-2xl font-bold text-primary">$<?php echo number_format($worker['hourly_rate'] * 2, 2); ?></span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Based on ~2 hours at $<?php echo $worker['hourly_rate']; ?>/hr</p>
            </div>
        </form>
    </div>

    <!-- Fixed Bottom Button -->
    <div class="fixed bottom-0 left-0 right-0 p-4 bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-sm border-t border-gray-200 dark:border-gray-800">
        <button type="submit" form="bookingForm" onclick="document.querySelector('form').submit()" class="w-full max-w-2xl mx-auto block py-4 rounded-xl bg-primary text-white font-bold hover:bg-primary/90">
            Send Booking Request
        </button>
        <p class="text-xs text-center mt-2 text-gray-600 dark:text-gray-400">This is a request. The worker must confirm the booking.</p>
    </div>

    <script>
        document.querySelector('form').id = 'bookingForm';
    </script>
</body>
</html>