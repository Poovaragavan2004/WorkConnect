<?php
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $profession = mysqli_real_escape_string($conn, $_POST['profession']);
    $experience_years = (int)$_POST['experience_years'];
    $hourly_rate = (float)$_POST['hourly_rate'];
    $service_area = mysqli_real_escape_string($conn, $_POST['service_area']);
    $bio = mysqli_real_escape_string($conn, $_POST['bio']);
    
    // Check if email exists
    $check = "SELECT * FROM workers WHERE email = '$email'";
    $result = mysqli_query($conn, $check);
    
    if (mysqli_num_rows($result) > 0) {
        $error = "Email already registered";
    } else {
        $query = "INSERT INTO workers (full_name, email, password, phone, profession, experience_years, hourly_rate, service_area, bio, status) 
                  VALUES ('$full_name', '$email', '$password', '$phone', '$profession', $experience_years, $hourly_rate, '$service_area', '$bio', 'pending')";
        
        if (mysqli_query($conn, $query)) {
            $success = "Registration successful! Your account is pending admin approval.";
            header("refresh:3;url=worker_login.php");
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Worker Registration - LocalWorks</title>
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
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-2xl mx-auto space-y-6">
            
            <!-- Header -->
            <div class="text-center">
                <div class="flex justify-center items-center gap-3 mb-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary text-white">
                        <span class="material-symbols-outlined !text-3xl">engineering</span>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">LocalWorks</p>
                </div>
                <h1 class="text-gray-900 dark:text-white tracking-tight text-3xl font-bold">Become a Worker</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2 text-base">Join our platform and start earning</p>
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
            
            <!-- Registration Form -->
            <form method="POST" class="bg-white dark:bg-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-800 space-y-4">
                
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Personal Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="flex flex-col">
                        <p class="text-gray-900 dark:text-white text-sm font-medium pb-2">Full Name *</p>
                        <input 
                            name="full_name"
                            class="form-input rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white h-12 px-4" 
                            placeholder="John Doe" 
                            type="text" 
                            required
                        />
                    </label>
                    
                    <label class="flex flex-col">
                        <p class="text-gray-900 dark:text-white text-sm font-medium pb-2">Email *</p>
                        <input 
                            name="email"
                            class="form-input rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white h-12 px-4" 
                            placeholder="john@example.com" 
                            type="email" 
                            required
                        />
                    </label>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="flex flex-col">
                        <p class="text-gray-900 dark:text-white text-sm font-medium pb-2">Password *</p>
                        <input 
                            name="password"
                            class="form-input rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white h-12 px-4" 
                            placeholder="Create password" 
                            type="password"
                            required
                        />
                    </label>
                    
                    <label class="flex flex-col">
                        <p class="text-gray-900 dark:text-white text-sm font-medium pb-2">Phone *</p>
                        <input 
                            name="phone"
                            class="form-input rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white h-12 px-4" 
                            placeholder="(555) 123-4567" 
                            type="tel"
                            required
                        />
                    </label>
                </div>

                <hr class="border-gray-200 dark:border-gray-800 my-4"/>
                
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Professional Details</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="flex flex-col">
                        <p class="text-gray-900 dark:text-white text-sm font-medium pb-2">Profession *</p>
                        <select 
                            name="profession"
                            class="form-select rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white h-12 px-4"
                            required
                        >
                            <option value="">Select profession</option>
                            <option value="Plumber">Plumber</option>
                            <option value="Electrician">Electrician</option>
                            <option value="Carpenter">Carpenter</option>
                            <option value="Landscaper">Landscaper</option>
                            <option value="Painter">Painter</option>
                            <option value="Handyman">Handyman</option>
                            <option value="Other">Other</option>
                        </select>
                    </label>
                    
                    <label class="flex flex-col">
                        <p class="text-gray-900 dark:text-white text-sm font-medium pb-2">Experience (Years) *</p>
                        <input 
                            name="experience_years"
                            class="form-input rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white h-12 px-4" 
                            placeholder="5" 
                            type="number"
                            min="0"
                            required
                        />
                    </label>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="flex flex-col">
                        <p class="text-gray-900 dark:text-white text-sm font-medium pb-2">Hourly Rate ($) *</p>
                        <input 
                            name="hourly_rate"
                            class="form-input rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white h-12 px-4" 
                            placeholder="50.00" 
                            type="number"
                            step="0.01"
                            min="0"
                            required
                        />
                    </label>
                    
                    <label class="flex flex-col">
                        <p class="text-gray-900 dark:text-white text-sm font-medium pb-2">Service Area *</p>
                        <input 
                            name="service_area"
                            class="form-input rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white h-12 px-4" 
                            placeholder="20-mile radius" 
                            type="text"
                            required
                        />
                    </label>
                </div>
                
                <label class="flex flex-col">
                    <p class="text-gray-900 dark:text-white text-sm font-medium pb-2">Bio / Description *</p>
                    <textarea 
                        name="bio"
                        class="form-textarea rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-3" 
                        placeholder="Tell customers about your experience and services..."
                        rows="4"
                        required
                    ></textarea>
                </label>
                
                <!-- Submit Button -->
                <button type="submit" class="w-full h-12 flex items-center justify-center rounded-lg bg-primary text-white text-base font-semibold transition-colors hover:bg-primary/90">
                    Submit Application
                </button>
                
                <p class="text-sm text-center text-gray-600 dark:text-gray-400">
                    Your account will be reviewed by admin before approval
                </p>
            </form>
            
            <!-- Login Link -->
            <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                Already have an account? <a class="font-semibold text-primary hover:underline" href="worker_login.php">Log In</a>
            </p>
        </div>
    </div>
</body>
</html>