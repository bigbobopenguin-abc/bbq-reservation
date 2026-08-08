<?php
require_once 'admin_check.php';
require_once 'dbinfo.php';

$active_page = 'dashboard';

$total_reservations = $mysqli->query("SELECT COUNT(*) AS c FROM reservations")->fetch_assoc()['c'];
$total_users = $mysqli->query("SELECT COUNT(*) AS c FROM users WHERE role = 'user'")->fetch_assoc()['c'];
$total_pits = $mysqli->query("SELECT COUNT(*) AS c FROM bbq_pits WHERE status = 'active'")->fetch_assoc()['c'];
$upcoming = $mysqli->query("SELECT COUNT(*) AS c FROM reservations WHERE status = 'confirmed' AND booking_date >= CURDATE()")->fetch_assoc()['c'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - BBQ Hub</title>
<link rel="stylesheet" href="css/css_reset.css">
<link rel="stylesheet" href="css/global.css">
<link rel="stylesheet" href="css/admin.css">
</head>
<body>

<?php include_once("admin_header.php") ?>

<h1 class="admin-page-title">Dashboard</h1>
<p class="admin-page-sub">Quick overview of your BBQ Hub system.</p>

<div class="stat-grid">
    <div class="stat-card">
        <span class="stat-label">Total Reservations</span>
        <span class="stat-value"><?php echo (int)$total_reservations; ?></span>
    </div>
    <div class="stat-card">
        <span class="stat-label">Upcoming Bookings</span>
        <span class="stat-value"><?php echo (int)$upcoming; ?></span>
    </div>
    <div class="stat-card">
        <span class="stat-label">Registered Users</span>
        <span class="stat-value"><?php echo (int)$total_users; ?></span>
    </div>
    <div class="stat-card">
        <span class="stat-label">Active BBQ Pits</span>
        <span class="stat-value"><?php echo (int)$total_pits; ?></span>
    </div>
</div>


</body>
</html>