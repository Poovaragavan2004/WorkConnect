<?php
require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    
    $query = "SELECT * FROM workers WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $worker = mysqli_fetch_assoc($result);
        if (password_verify($password, $worker['password'])) {
            if ($worker['status'] == 'pending') {
                $error = "Your account is pending approval by admin";
            } elseif ($worker['status'] == 'suspended') {
                $error = "Your account has been suspended";
            } else {
                $_SESSION['worker_id'] = $worker['id'];
                $_SESSION['worker_name'] = $worker['full_name'];
                header("Location: worker_dashboard.php");
                exit();
            }
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
    <title>Worker Login - LocalWorks</title>
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
                        <span class="material-symbols-outlined !text-3xl">engineering</span>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">LocalWorks Worker</p>
                </div>
                
                <!-- Headline -->
                <div class="text-center">
                    <h1 class="text-gray-900 dark:text-white tracking-tight text-3xl font-bold">Worker Login</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2 text-base">Access your worker dashboard</p>
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
                        <input 
                            name="email"
                            class="form-input rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white h-12 px-4" 
                            placeholder="Enter your email" 
                            type="email" 
                            required
                        />
                    </label>
                    
                    <!-- Password -->
                    <label class="flex flex-col flex-1">
                        <p class="text-gray-900 dark:text-white text-sm font-medium pb-2">Password</p>
                        <input 
                            name="password"
                            class="form-input rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white h-12 px-4" 
                            placeholder="Enter your password" 
                            type="password"
                            required
                        />
                    </label>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="w-full h-12 flex items-center justify-center rounded-lg bg-primary text-white text-base font-semibold transition-colors hover:bg-primary/90">
                        Log In
                    </button>
                </form>
                
                <!-- Links -->
                <div class="text-center space-y-2">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Don't have an account? <a class="font-semibold text-primary hover:underline" href="worker_register.php">Register as Worker</a>
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <a class="text-primary hover:underline" href="customer_login.php">← Back to Customer Login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>