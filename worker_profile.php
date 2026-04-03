<?php
require_once 'config.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_login.php");
    exit();
}

$worker_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$query = "SELECT * FROM workers WHERE id = $worker_id AND status = 'approved'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: search_workers.php");
    exit();
}

$worker = mysqli_fetch_assoc($result);
$skills_array = $worker['skills'] ? explode(',', $worker['skills']) : [];
?>
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?php echo htmlspecialchars($worker['full_name']); ?> - Profile</title>
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
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Profile</h1>
            <div class="w-10"></div>
        </div>
    </div>

    <div class="max-w-2xl mx-auto p-4 space-y-6">
        
        <!-- Profile Header -->
        <div class="bg-white dark:bg-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-800 text-center">
            <div class="flex justify-center mb-4">
                <div class="relative">
                    <div class="w-32 h-32 rounded-full bg-gray-300 dark:bg-gray-700 flex items-center justify-center">
                        <span class="material-symbols-outlined text-5xl text-gray-600 dark:text-gray-400">person</span>
                    </div>
                    <?php if ($worker['is_available']): ?>
                    <div class="absolute bottom-2 right-2 w-8 h-8 bg-green-500 rounded-full border-4 border-white dark:border-gray-900"></div>
                    <?php endif; ?>
                </div>
            </div>
            
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo htmlspecialchars($worker['full_name']); ?></h2>
            <p class="text-gray-600 dark:text-gray-400 mt-1"><?php echo htmlspecialchars($worker['profession']); ?></p>
            
            <div class="flex items-center justify-center gap-2 mt-3">
                <span class="material-symbols-outlined text-yellow-400" style="font-variation-settings: 'FILL' 1;">star</span>
                <span class="text-gray-900 dark:text-white font-semibold"><?php echo $worker['rating']; ?></span>
                <span class="text-gray-600 dark:text-gray-400">(<?php echo $worker['total_reviews']; ?> reviews)</span>
            </div>

            <div class="flex gap-3 mt-6">
                <a href="tel:<?php echo $worker['phone']; ?>" class="flex-1 px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-800 text-gray-900 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-700">
                    <span class="material-symbols-outlined text-sm align-middle">call</span> Call
                </a>
                <a href="book_worker.php?worker_id=<?php echo $worker['id']; ?>" class="flex-1 px-4 py-2 rounded-lg bg-primary text-white hover:bg-primary/90">
                    Book Now
                </a>
            </div>
        </div>

        <!-- About Section -->
        <div class="bg-white dark:bg-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-800">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">About</h3>
            <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                <?php echo nl2br(htmlspecialchars($worker['bio'])); ?>
            </p>
        </div>

        <!-- Trust Signals -->
        <?php if ($worker['background_checked'] || $worker['insured']): ?>
        <div class="bg-white dark:bg-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-800">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Trust Signals</h3>
            <div class="flex flex-wrap gap-2">
                <?php if ($worker['background_checked']): ?>
                <div class="flex items-center gap-2 bg-primary/10 text-primary px-3 py-1.5 rounded-full">
                    <span class="material-symbols-outlined text-sm">verified_user</span>
                    <span class="text-sm font-medium">Background Checked</span>
                </div>
                <?php endif; ?>
                <?php if ($worker['insured']): ?>
                <div class="flex items-center gap-2 bg-primary/10 text-primary px-3 py-1.5 rounded-full">
                    <span class="material-symbols-outlined text-sm">shield</span>
                    <span class="text-sm font-medium">Insured</span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Skills -->
        <?php if (!empty($skills_array)): ?>
        <div class="bg-white dark:bg-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-800">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Skills</h3>
            <div class="flex flex-wrap gap-2">
                <?php foreach($skills_array as $skill): ?>
                <span class="px-3 py-1.5 rounded-lg bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm font-medium">
                    <?php echo htmlspecialchars(trim($skill)); ?>
                </span>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Details -->
        <div class="bg-white dark:bg-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-800">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Details</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Hourly Rate</span>
                    <span class="text-gray-900 dark:text-white font-semibold">$<?php echo number_format($worker['hourly_rate'], 2); ?>/hr</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Service Area</span>
                    <span class="text-gray-900 dark:text-white font-semibold"><?php echo htmlspecialchars($worker['service_area']); ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Experience</span>
                    <span class="text-gray-900 dark:text-white font-semibold"><?php echo $worker['experience_years']; ?>+ Years</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Fixed Bottom CTA -->
    <div class="fixed bottom-0 left-0 right-0 p-4 bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-sm border-t border-gray-200 dark:border-gray-800">
        <a href="book_worker.php?worker_id=<?php echo $worker['id']; ?>" class="block w-full max-w-2xl mx-auto py-4 rounded-xl bg-primary text-white font-bold text-center hover:bg-primary/90">
            Request Quote
        </a>
    </div>
</body>
</html>