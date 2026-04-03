<?php
require_once 'config.php';

if (!isset($_SESSION['worker_id'])) {
    header("Location: worker_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booking_id = (int)$_POST['booking_id'];
    $action = $_POST['action']; // 'accept' or 'decline'
    $worker_id = $_SESSION['worker_id'];
    
    // Verify booking belongs to this worker
    $verify_query = "SELECT * FROM bookings WHERE id = $booking_id AND worker_id = $worker_id";
    $verify_result = mysqli_query($conn, $verify_query);
    
    if (mysqli_num_rows($verify_result) > 0) {
        if ($action == 'accept') {
            $new_status = 'accepted';
        } elseif ($action == 'decline') {
            $new_status = 'declined';
        }
        
        $update_query = "UPDATE bookings SET status = '$new_status' WHERE id = $booking_id";
        mysqli_query($conn, $update_query);
    }
}

header("Location: worker_dashboard.php");
exit();
?>