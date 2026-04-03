<?php
require_once 'config.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_login.php");
    exit();
}

$search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';

// Build query
$where = "WHERE status = 'approved'";
if ($search_query) {
    $where .= " AND (full_name LIKE '%$search_query%' OR profession LIKE '%$search_query%' OR skills LIKE '%$search_query%')";
}
if ($category) {
    $where .= " AND profession LIKE '%$category%'";
}

$query = "SELECT * FROM workers $where ORDER BY rating DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Find Workers - LocalWorks</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#007BFF",
                        "background-light": "#F8F9FA",
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
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Find Workers</h1>
            <div class="w-10"></div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto p-4 space-y-6">
        
        <!-- Search Bar -->
        <form method="GET" class="space-y-4">
            <div class="relative">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                <input 
                    name="search"
                    type="text" 
                    placeholder="Search by name, profession, or skill..."
                    value="<?php echo htmlspecialchars($search_query); ?>"
                    class="w-full pl-12 pr-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
                />
            </div>
            
            <!-- Category Filter -->
            <div class="flex gap-2 overflow-x-auto pb-2">
                <a href="search_workers.php" class="px-4 py-2 rounded-lg whitespace-nowrap <?php echo !$category ? 'bg-primary text-white' : 'bg-white dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700'; ?>">
                    All
                </a>
                <a href="?category=Plumber" class="px-4 py-2 rounded-lg whitespace-nowrap <?php echo $category == 'Plumber' ? 'bg-primary text-white' : 'bg-white dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700'; ?>">
                    Plumber
                </a>
                <a href="?category=Electrician" class="px-4 py-2 rounded-lg whitespace-nowrap <?php echo $category == 'Electrician' ? 'bg-primary text-white' : 'bg-white dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700'; ?>">
                    Electrician
                </a>
                <a href="?category=Landscaper" class="px-4 py-2 rounded-lg whitespace-nowrap <?php echo $category == 'Landscaper' ? 'bg-primary text-white' : 'bg-white dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700'; ?>">
                    Landscaper
                </a>
                <a href="?category=Carpenter" class="px-4 py-2 rounded-lg whitespace-nowrap <?php echo $category == 'Carpenter' ? 'bg-primary text-white' : 'bg-white dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700'; ?>">
                    Carpenter
                </a>
            </div>
        </form>

        <!-- Results Count -->
        <div class="text-gray-600 dark:text-gray-400">
            Found <?php echo mysqli_num_rows($result); ?> workers
        </div>

        <!-- Workers List -->
        <div class="space-y-4">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while($worker = mysqli_fetch_assoc($result)): ?>
                <div class="bg-white dark:bg-gray-900 rounded-xl p-4 border border-gray-200 dark:border-gray-800 hover:border-primary transition-colors">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 rounded-full bg-gray-300 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-2xl text-gray-600 dark:text-gray-400">person</span>
                        </div>
                        
                        <div class="flex-1">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="font-bold text-lg text-gray-900 dark:text-white"><?php echo htmlspecialchars($worker['full_name']); ?></h3>
                                    <p class="text-gray-600 dark:text-gray-400"><?php echo htmlspecialchars($worker['profession']); ?></p>
                                </div>
                                <?php if ($worker['is_available']): ?>
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                    Available
                                </span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="flex items-center gap-2 mt-2">
                                <span class="material-symbols-outlined text-yellow-400 text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="font-semibold text-gray-900 dark:text-white"><?php echo $worker['rating']; ?></span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">(<?php echo $worker['total_reviews']; ?> reviews)</span>
                            </div>
                            
                            <div class="flex items-center gap-4 mt-2 text-sm text-gray-600 dark:text-gray-400">
                                <span>💰 $<?php echo $worker['hourly_rate']; ?>/hr</span>
                                <span>📍 <?php echo htmlspecialchars($worker['service_area']); ?></span>
                            </div>
                            
                            <?php if ($worker['bio']): ?>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 line-clamp-2">
                                <?php echo htmlspecialchars($worker['bio']); ?>
                            </p>
                            <?php endif; ?>
                            
                            <div class="flex gap-2 mt-3">
                                <a href="worker_profile.php?id=<?php echo $worker['id']; ?>" class="flex-1 text-center px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-800">
                                    View Profile
                                </a>
                                <a href="book_worker.php?worker_id=<?php echo $worker['id']; ?>" class="flex-1 text-center px-4 py-2 rounded-lg bg-primary text-white hover:bg-primary/90">
                                    Book Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="text-center py-12">
                    <span class="material-symbols-outlined text-6xl text-gray-400 mb-4">search_off</span>
                    <p class="text-gray-600 dark:text-gray-400">No workers found</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>