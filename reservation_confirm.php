<?php
require_once 'check_session.php';
require_once 'dbinfo.php';

$reservation_id = (int)($_GET['reservation_id'] ?? 0);
$user_id = $_SESSION['uid'];

$stmt = $mysqli->prepare(
    "SELECT r.reservation_id, r.booking_date, r.start_time, r.end_time, r.status,
            p.name, p.location, p.image_path, p.price_per_session
     FROM reservations r
     JOIN bbq_pits p ON r.pit_id = p.pit_id
     WHERE r.reservation_id = ? AND r.user_id = ?"
);
$stmt->bind_param("ii", $reservation_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();
$stmt->close();

// If it doesn't exist, or doesn't belong to this user, send them elsewhere
if (!$booking) {
    header("Location: products.php");
    exit();
}

// Format date/time nicely for display
$formatted_date = date("l, j F Y", strtotime($booking['booking_date']));
$formatted_start = date("g:i A", strtotime($booking['start_time']));
$formatted_end = date("g:i A", strtotime($booking['end_time']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reservation Confirmed - BBQ Hub</title>
<link rel="stylesheet" href="css/css_reset.css">
<link rel="stylesheet" href="css/global.css">
<link rel="stylesheet" href="css/reservation.css">
<link rel="stylesheet" href="css/confirm.css">
</head>
<body>

<?php include_once("header.php") ?>

<main id="main">
    <div class="booking-card confirm-card">

        <div class="confirm-icon">
            <svg viewBox="0 0 24 24" width="32" height="32" aria-hidden="true"><path fill="currentColor" d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm-1.2 14.4L6 11.6l1.4-1.4 3.4 3.4 6.4-6.4L18.6 8.6l-7.8 7.8z"/></svg>
        </div>

        <h1 class="confirm-title">Reservation Confirmed!</h1>
        <p class="confirm-sub">Your BBQ pit is booked. See you there!</p>

        <div class="pit-info-row">
            <div class="pit-thumb" style="background-image: url('<?php echo htmlspecialchars($booking['image_path'] ?: 'images/pit-placeholder.jpg'); ?>');"></div>
            <div class="pit-info-text">
                <h2><?php echo htmlspecialchars($booking['name']); ?></h2>
                <p><?php echo htmlspecialchars($booking['location']); ?></p>
            </div>
        </div>

        <div class="booking-detail-row">
            <span class="detail-label">Booking Ref</span>
            <span class="detail-value">#<?php echo str_pad($booking['reservation_id'], 6, '0', STR_PAD_LEFT); ?></span>
        </div>

        <div class="booking-detail-row">
            <span class="detail-label">Date</span>
            <span class="detail-value"><?php echo htmlspecialchars($formatted_date); ?></span>
        </div>

        <div class="booking-detail-row">
            <span class="detail-label">Time</span>
            <span class="detail-value"><?php echo htmlspecialchars($formatted_start . " - " . $formatted_end); ?></span>
        </div>

        <div class="booking-detail-row price-row">
            <span class="detail-label">Price</span>
            <span class="detail-value price">$<?php echo number_format($booking['price_per_session'], 2); ?> <span class="unit">/ day</span></span>
        </div>

        <div class="confirm-actions">
            <a href="my_reservations.php" class="btn btn-accent btn-block btn-pill">View My Reservations</a>
            <a href="products.php" class="btn btn-outline-dark btn-block btn-pill">Browse More Pits</a>
        </div>

    </div>
</main>

<?php include_once("footer.php") ?>

</body>
</html>