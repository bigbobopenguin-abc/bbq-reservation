<?php
require_once 'admin_check.php';
require_once 'dbinfo.php';

$active_page = 'reservations';

$reservation_id = (int)($_GET['reservation_id'] ?? 0);

$stmt = $mysqli->prepare(
    "SELECT r.reservation_id, r.booking_date, r.start_time, r.end_time, r.status,
            u.username, u.email, p.name AS pit_name
     FROM reservations r
     JOIN users u ON r.user_id = u.user_id
     JOIN bbq_pits p ON r.pit_id = p.pit_id
     WHERE r.reservation_id = ?"
);
$stmt->bind_param("i", $reservation_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();
$stmt->close();

if (!$booking) {
    header("Location: admin_reservations.php");
    exit();
}

$errors = [];
if (isset($_GET['error'])) {
    $errors[] = "Please check the details - make sure end time is after start time.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Reservation - BBQ Hub Admin</title>
<link rel="stylesheet" href="css/css_reset.css">
<link rel="stylesheet" href="css/global.css">
<link rel="stylesheet" href="css/admin.css">
</head>
<body>

<?php include_once("admin_header.php") ?>

<a href="admin_reservations.php" class="back-link">
    <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><path fill="currentColor" d="M15.4 7.4 14 6l-6 6 6 6 1.4-1.4L10.8 12z"/></svg>
    Back to All Reservations
</a>

<h1 class="admin-page-title">Edit Reservation #<?php echo str_pad($booking['reservation_id'], 6, '0', STR_PAD_LEFT); ?></h1>
<p class="admin-page-sub"><?php echo htmlspecialchars($booking['username']); ?> (<?php echo htmlspecialchars($booking['email']); ?>) &bull; <?php echo htmlspecialchars($booking['pit_name']); ?></p>

<?php if (!empty($errors)): ?>
    <div class="error-box">
        <ul><?php foreach ($errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?></ul>
    </div>
<?php endif; ?>

<div class="admin-card">
    <form action="admin_reservation_process.php" method="POST" class="admin-add-form">
        <input type="hidden" name="reservation_id" value="<?php echo (int)$booking['reservation_id']; ?>">

        <div class="form-group">
            <label for="booking_date">Booking Date</label>
            <input type="date" id="booking_date" name="booking_date" required
                   value="<?php echo htmlspecialchars($booking['booking_date']); ?>">
        </div>

        <div class="form-group">
            <label for="start_time">Start Time</label>
            <input type="time" id="start_time" name="start_time" required
                   value="<?php echo htmlspecialchars($booking['start_time']); ?>">
        </div>

        <div class="form-group">
            <label for="end_time">End Time</label>
            <input type="time" id="end_time" name="end_time" required
                   value="<?php echo htmlspecialchars($booking['end_time']); ?>">
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="confirmed" <?php echo $booking['status'] === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                <option value="cancelled" <?php echo $booking['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
            </select>
        </div>

        <div class="form-group-wide edit-actions">
            <button type="submit" class="btn btn-accent">Save Changes</button>
            <a href="admin_reservations.php" class="btn btn-outline-dark">Cancel</a>
        </div>
    </form>
</div>



</body>
</html>