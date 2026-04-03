<?php
require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    
    $query = "SELECT * FROM customers WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $customer = mysqli_fetch_assoc($result);
        if (password_verify($password, $customer['password'])) {
            $_SESSION['customer_id'] = $customer['id'];
            $_SESSION['customer_name'] = $customer['full_name'];
            header("Location: customer_dashboard.php");
            exit();
        } else {
            $error = "Invalid password";
        }
    } else {
        $error = "Email not found";
    }
}
?>
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Customer Login - LocalWorks</title>
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
<body class="font-display">
    <div class="relative flex min-h-screen w-full flex-col bg-background-light dark:bg-background-dark overflow-x-hidden">
        <div class="flex-grow flex flex-col justify-center items-center p-4 sm:p-6 lg:p-8">
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
                    <h1 class="text-gray-900 dark:text-white tracking-tight text-3xl font-bold">Welcome Back!</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2 text-base">Log in to find local help instantly.</p>
                </div>

                <!-- Error Message -->
                <?php if ($error): ?>
                <div class="bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg">
                    <?php echo $error; ?>
                </div>
                <?php endif; ?>
                
                <!-- Login Form -->
                <form method="POST" class="space-y-4">
                    
                    <!-- Email -->
                    <label class="flex flex-col flex-1">
                        <p class="text-gray-900 dark:text-white text-sm font-medium pb-2">Email Address</p>
                        <div class="flex w-full flex-1 items-stretch rounded-lg border border-gray-300 dark:border-gray-700 focus-within:ring-2 focus-within:ring-primary">
                            <span class="material-symbols-outlined flex items-center justify-center pl-4 pr-2 text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 rounded-l-lg">mail</span>
                            <input 
                                name="email"
                                class="form-input flex w-full min-w-0 flex-1 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-0 focus:ring-0 h-12 placeholder:text-gray-500 dark:placeholder:text-gray-400 p-3 text-base border-0 rounded-r-lg" 
                                placeholder="Enter your email" 
                                type="email" 
                                required
                            />
                        </div>
                    </label>
                    
                    <!-- Password -->
                    <label class="flex flex-col flex-1">
                        <div class="flex justify-between items-baseline">
                            <p class="text-gray-900 dark:text-white text-sm font-medium pb-2">Password</p>
                            <a class="text-primary text-sm font-medium hover:underline" href="#">Forgot Password?</a>
                        </div>
                        <div class="flex w-full flex-1 items-stretch rounded-lg border border-gray-300 dark:border-gray-700 focus-within:ring-2 focus-within:ring-primary">
                            <span class="material-symbols-outlined flex items-center justify-center pl-4 pr-2 text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 rounded-l-lg">lock</span>
                            <input 
                                name="password"
                                class="form-input flex w-full min-w-0 flex-1 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-0 focus:ring-0 h-12 placeholder:text-gray-500 dark:placeholder:text-gray-400 p-3 text-base border-0" 
                                placeholder="Enter your password" 
                                type="password"
                                required
                            />
                            <button type="button" class="flex items-center justify-center px-4 text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 rounded-r-lg">
                                <span class="material-symbols-outlined">visibility</span>
                            </button>
                        </div>
                    </label>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="w-full h-12 flex items-center justify-center rounded-lg bg-primary text-white text-base font-semibold transition-colors hover:bg-primary/90">
                        Log In
                    </button>
                </form>
                
                <!-- Divider -->
                <div class="flex items-center gap-4">
                    <div class="h-px flex-1 bg-gray-300 dark:bg-gray-700"></div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">OR</p>
                    <div class="h-px flex-1 bg-gray-300 dark:bg-gray-700"></div>
                </div>
                
                <!-- Role Selection -->
                <div class="space-y-3">
                    <a href="worker_login.php" class="w-full h-12 flex items-center justify-center gap-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-base font-medium transition-colors hover:bg-gray-100 dark:hover:bg-gray-700">
                        <span class="material-symbols-outlined">engineering</span>
                        Login as Worker
                    </a>
                    <a href="admin_login.php" class="w-full h-12 flex items-center justify-center gap-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-base font-medium transition-colors hover:bg-gray-100 dark:hover:bg-gray-700">
                        <span class="material-symbols-outlined">admin_panel_settings</span>
                        Login as Admin
                    </a>
                </div>
                
                <!-- Sign Up Link -->
                <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                    Don't have an account? <a class="font-semibold text-primary hover:underline" href="customer_register.php">Sign Up</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>