<?php
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    // Check if email exists
    $check = "SELECT * FROM customers WHERE email = '$email'";
    $result = mysqli_query($conn, $check);
    
    if (mysqli_num_rows($result) > 0) {
        $error = "Email already registered";
    } else {
        $query = "INSERT INTO customers (full_name, email, password, phone, address) VALUES ('$full_name', '$email', '$password', '$phone', '$address')";
        
        if (mysqli_query($conn, $query)) {
            $success = "Registration successful! Redirecting to login...";
            header("refresh:2;url=customer_login.php");
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
    <title>Customer Registration - LocalWorks</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#4A90E2",
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
    <div class="min-h-screen flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md mx-auto space-y-6">
            
            <!-- Logo -->
            <div class="flex justify-center items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary text-white">
                    <span class="material-symbols-outlined !text-3xl">handyman</span>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">LocalWorks</p>
            </div>
            
            <!-- Headline -->
            <div class="text-center">
                <h1 class="text-gray-900 dark:text-white tracking-tight text-3xl font-bold">Create Account</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2 text-base">Join LocalWorks as a Customer</p>
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
            <form method="POST" class="space-y-4">
                
                <!-- Full Name -->
                <label class="flex flex-col">
                    <p class="text-gray-900 dark:text-white text-sm font-medium pb-2">Full Name</p>
                    <input 
                        name="full_name"
                        class="form-input rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white h-12 px-4" 
                        placeholder="Enter your full name" 
                        type="text" 
                        required
                    />
                </label>
                
                <!-- Email -->
                <label class="flex flex-col">
                    <p class="text-gray-900 dark:text-white text-sm font-medium pb-2">Email Address</p>
                    <input 
                        name="email"
                        class="form-input rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white h-12 px-4" 
                        placeholder="Enter your email" 
                        type="email" 
                        required
                    />
                </label>
                
                <!-- Password -->
                <label class="flex flex-col">
                    <p class="text-gray-900 dark:text-white text-sm font-medium pb-2">Password</p>
                    <input 
                        name="password"
                        class="form-input rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white h-12 px-4" 
                        placeholder="Create a password" 
                        type="password"
                        required
                    />
                </label>
                
                <!-- Phone -->
                <label class="flex flex-col">
                    <p class="text-gray-900 dark:text-white text-sm font-medium pb-2">Phone Number</p>
                    <input 
                        name="phone"
                        class="form-input rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white h-12 px-4" 
                        placeholder="(123) 456-7890" 
                        type="tel"
                        required
                    />
                </label>
                
                <!-- Address -->
                <label class="flex flex-col">
                    <p class="text-gray-900 dark:text-white text-sm font-medium pb-2">Address</p>
                    <textarea 
                        name="address"
                        class="form-textarea rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-3" 
                        placeholder="Enter your address"
                        rows="3"
                        required
                    ></textarea>
                </label>
                
                <!-- Submit Button -->
                <button type="submit" class="w-full h-12 flex items-center justify-center rounded-lg bg-primary text-white text-base font-semibold transition-colors hover:bg-primary/90">
                    Create Account
                </button>
            </form>
            
            <!-- Login Link -->
            <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                Already have an account? <a class="font-semibold text-primary hover:underline" href="customer_login.php">Log In</a>
            </p>
        </div>
    </div>
</body>
</html>