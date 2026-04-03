<?php
require_once 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle approve/suspend actions
if (isset($_POST['action']) && isset($_POST['worker_id'])) {
    $worker_id = (int)$_POST['worker_id'];
    $action = $_POST['action'];
    
    if ($action == 'approve') {
        mysqli_query($conn, "UPDATE workers SET status = 'approved' WHERE id = $worker_id");
    } elseif ($action == 'suspend') {
        mysqli_query($conn, "UPDATE workers SET status = 'suspended' WHERE id = $worker_id");
    } elseif ($action == 'delete') {
        mysqli_query($conn, "DELETE FROM workers WHERE id = $worker_id");
    }
    
    header("Location: admin_workers.php");
    exit();
}

// Get filter
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$where = "";
if ($filter == 'pending') {
    $where = "WHERE status = 'pending'";
} elseif ($filter == 'approved') {
    $where = "WHERE status = 'approved'";
} elseif ($filter == 'suspended') {
    $where = "WHERE status = 'suspended'";
}

$query = "SELECT * FROM workers $where ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Manage Workers - Admin</title>
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
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Manage Workers</h1>
            <div class="w-10"></div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto p-4 space-y-6">
        
        <!-- Filter Tabs -->
        <div class="flex gap-2 overflow-x-auto pb-2">
            <a href="?filter=all" class="px-4 py-2 rounded-lg whitespace-nowrap <?php echo $filter == 'all' ? 'bg-primary text-white' : 'bg-white dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700'; ?>">
                All Workers
            </a>
            <a href="?filter=pending" class="px-4 py-2 rounded-lg whitespace-nowrap <?php echo $filter == 'pending' ? 'bg-primary text-white' : 'bg-white dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700'; ?>">
                Pending Approval
            </a>
            <a href="?filter=approved" class="px-4 py-2 rounded-lg whitespace-nowrap <?php echo $filter == 'approved' ? 'bg-primary text-white' : 'bg-white dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700'; ?>">
                Approved
            </a>
            <a href="?filter=suspended" class="px-4 py-2 rounded-lg whitespace-nowrap <?php echo $filter == 'suspended' ? 'bg-primary text-white' : 'bg-white dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700'; ?>">
                Suspended
            </a>
        </div>

        <!-- Workers Table -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase">Worker</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase">Profession</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase">Rate</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase">Rating</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while($worker = mysqli_fetch_assoc($result)): ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                                            <span class="material-symbols-outlined text-gray-600 dark:text-gray-400">person</span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white"><?php echo htmlspecialchars($worker['full_name']); ?></p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo $worker['experience_years']; ?> yrs exp</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-900 dark:text-white"><?php echo htmlspecialchars($worker['email']); ?></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo htmlspecialchars($worker['phone']); ?></p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-900 dark:text-white"><?php echo htmlspecialchars($worker['profession']); ?></p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">$<?php echo number_format($worker['hourly_rate'], 2); ?>/hr</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        <?php 
                                            if($worker['status'] == 'pending') echo 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400';
                                            elseif($worker['status'] == 'approved') echo 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
                                            else echo 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
                                        ?>">
                                        <?php echo ucfirst($worker['status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-yellow-400 text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
                                        <span class="text-sm text-gray-900 dark:text-white"><?php echo $worker['rating']; ?></span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">(<?php echo $worker['total_reviews']; ?>)</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <?php if ($worker['status'] == 'pending'): ?>
                                        <form method="POST" class="inline">
                                            <input type="hidden" name="worker_id" value="<?php echo $worker['id']; ?>"/>
                                            <input type="hidden" name="action" value="approve"/>
                                            <button type="submit" class="px-3 py-1 rounded bg-green-600 text-white text-xs hover:bg-green-700">
                                                Approve
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                        
                                        <?php if ($worker['status'] == 'approved'): ?>
                                        <form method="POST" class="inline">
                                            <input type="hidden" name="worker_id" value="<?php echo $worker['id']; ?>"/>
                                            <input type="hidden" name="action" value="suspend"/>
                                            <button type="submit" class="px-3 py-1 rounded bg-red-600 text-white text-xs hover:bg-red-700">
                                                Suspend
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                        
                                        <?php if ($worker['status'] == 'suspended'): ?>
                                        <form method="POST" class="inline">
                                            <input type="hidden" name="worker_id" value="<?php echo $worker['id']; ?>"/>
                                            <input type="hidden" name="action" value="approve"/>
                                            <button type="submit" class="px-3 py-1 rounded bg-green-600 text-white text-xs hover:bg-green-700">
                                                Activate
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    No workers found
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>