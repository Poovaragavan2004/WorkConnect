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

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $profession = mysqli_real_escape_string($conn, $_POST['profession']);
    $experience_years = (int)$_POST['experience_years'];
    $hourly_rate = (float)$_POST['hourly_rate'];
    $service_area = mysqli_real_escape_string($conn, $_POST['service_area']);
    $bio = mysqli_real_escape_string($conn, $_POST['bio']);
    $skills = mysqli_real_escape_string($conn, $_POST['skills']);
    
    $update_query = "UPDATE workers SET 
                     full_name = '$full_name',
                     phone = '$phone',
                     profession = '$profession',
                     experience_years = $experience_years,
                     hourly_rate = $hourly_rate,
                     service_area = '$service_area',
                     bio = '$bio',
                     skills = '$skills'
                     WHERE id = $worker_id";
    
    if (mysqli_query($conn, $update_query)) {
        $success = "Profile updated successfully!";
        // Refresh worker data
        $result = mysqli_query($conn, $query);
        $worker = mysqli_fetch_assoc($result);
    } else {
        $error = "Failed to update profile";
    }
}
?>
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Edit Profile - LocalWorks</title>
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
            <a href="worker_dashboard.php" class="text-gray-600 dark:text-gray-400">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Edit Profile</h1>
            <div class="w-10"></div>
        </div>
    </div>

    <div class="max-w-2xl mx-auto p-4 space-y-6">
        
        <!-- Messages -->
        <?php if ($success): ?>
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg">
            <?php echo $success; ?>
        </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
        <div class="bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg">
            <?php echo $error; ?>
        </div>
        <?php endif; ?>

        <!-- Profile Form -->
        <form method="POST" class="space-y-6">
            
            <!-- Personal Info -->
            <div class="bg-white dark:bg-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-800 space-y-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Personal Information</h3>
                
                <label class="block">
                    <span class="text-gray-900 dark:text-white text-sm font-medium mb-2 block">Full Name</span>
                    <input 
                        name="full_name"
                        value="<?php echo htmlspecialchars($worker['full_name']); ?>"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                        required
                    />
                </label>
                
                <label class="block">
                    <span class="text-gray-900 dark:text-white text-sm font-medium mb-2 block">Phone</span>
                    <input 
                        name="phone"
                        value="<?php echo htmlspecialchars($worker['phone']); ?>"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                        required
                    />
                </label>
                
                <label class="block">
                    <span class="text-gray-900 dark:text-white text-sm font-medium mb-2 block">Email (Cannot be changed)</span>
                    <input 
                        value="<?php echo htmlspecialchars($worker['email']); ?>"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400"
                        disabled
                    />
                </label>
            </div>

            <!-- Professional Info -->
            <div class="bg-white dark:bg-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-800 space-y-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Professional Details</h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <label class="block">
                        <span class="text-gray-900 dark:text-white text-sm font-medium mb-2 block">Profession</span>
                        <select 
                            name="profession"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                            required
                        >
                            <option value="Plumber" <?php echo $worker['profession'] == 'Plumber' ? 'selected' : ''; ?>>Plumber</option>
                            <option value="Electrician" <?php echo $worker['profession'] == 'Electrician' ? 'selected' : ''; ?>>Electrician</option>
                            <option value="Carpenter" <?php echo $worker['profession'] == 'Carpenter' ? 'selected' : ''; ?>>Carpenter</option>
                            <option value="Landscaper" <?php echo $worker['profession'] == 'Landscaper' ? 'selected' : ''; ?>>Landscaper</option>
                            <option value="Painter" <?php echo $worker['profession'] == 'Painter' ? 'selected' : ''; ?>>Painter</option>
                            <option value="Other" <?php echo $worker['profession'] == 'Other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </label>
                    
                    <label class="block">
                        <span class="text-gray-900 dark:text-white text-sm font-medium mb-2 block">Experience (Years)</span>
                        <input 
                            name="experience_years"
                            type="number"
                            value="<?php echo $worker['experience_years']; ?>"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                            required
                        />
                    </label>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <label class="block">
                        <span class="text-gray-900 dark:text-white text-sm font-medium mb-2 block">Hourly Rate ($)</span>
                        <input 
                            name="hourly_rate"
                            type="number"
                            step="0.01"
                            value="<?php echo $worker['hourly_rate']; ?>"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                            required
                        />
                    </label>
                    
                    <label class="block">
                        <span class="text-gray-900 dark:text-white text-sm font-medium mb-2 block">Service Area</span>
                        <input 
                            name="service_area"
                            value="<?php echo htmlspecialchars($worker['service_area']); ?>"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                            required
                        />
                    </label>
                </div>
                
                <label class="block">
                    <span class="text-gray-900 dark:text-white text-sm font-medium mb-2 block">Skills (comma separated)</span>
                    <input 
                        name="skills"
                        value="<?php echo htmlspecialchars($worker['skills']); ?>"
                        placeholder="Plumbing, Pipe Repair, Installation"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                    />
                </label>
                
                <label class="block">
                    <span class="text-gray-900 dark:text-white text-sm font-medium mb-2 block">Bio / About Me</span>
                    <textarea 
                        name="bio"
                        rows="5"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                        required
                    ><?php echo htmlspecialchars($worker['bio']); ?></textarea>
                </label>
            </div>

            <!-- Stats (Read-only) -->
            <div class="bg-white dark:bg-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-800">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Your Stats</h3>
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div>
                        <p class="text-2xl font-bold text-primary"><?php echo $worker['rating']; ?></p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Rating</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-primary"><?php echo $worker['total_reviews']; ?></p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Reviews</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-primary"><?php echo $worker['experience_years']; ?>+</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Years Exp</p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-4 rounded-xl bg-primary text-white font-bold hover:bg-primary/90">
                Save Changes
            </button>
        </form>
    </div>
</body>
</html>