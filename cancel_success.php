<?php
require_once 'check_session.php';
require_once 'dbinfo.php';

$reservation_id = (int)($_GET['reservation_id'] ?? 0);
$user_id = $_SESSION['uid'];

// Fetch the now cancelled reservation, joined with pit details -
$stmt = $mysqli->prepare(
    "SELECT r.reservation_id, r.booking_date, r.start_time, r.end_time, r.status,
            p.name, p.location, p.image_path
     FROM reservations r
     JOIN bbq_pits p ON r.pit_id = p.pit_id
     WHERE r.reservation_id = ? AND r.user_id = ?"
);
$stmt->bind_param("ii", $reservation_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();
$stmt->close();

// Doesn't exist, doesn't belong to this user, or was never actually cancelled
if (!$booking || $booking['status'] !== 'cancelled') {
    header("Location: my_reservations.php");
    exit();
}

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
<title>Reservation Cancelled - BBQ Hub</title>
<link rel="stylesheet" href="css/css_reset.css">
<link rel="stylesheet" href="css/global.css">
<link rel="stylesheet" href="css/reservation.css">
<link rel="stylesheet" href="css/confirm.css">
</head>
<body>

<?php include_once("header.php") ?>

<main id="main">
    <div class="booking-card confirm-card">

        <div class="confirm-icon confirm-icon-neutral">
            <svg viewBox="0 0 24 24" width="32" height="32" aria-hidden="true"><path fill="currentColor" d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm3.5 13.1-1.4 1.4L12 14.4l-2.1 2.1-1.4-1.4 2.1-2.1-2.1-2.1 1.4-1.4 2.1 2.1 2.1-2.1 1.4 1.4-2.1 2.1z"/></svg>
        </div>

        <h1 class="confirm-title">Reservation Cancelled</h1>
        <p class="confirm-sub">Your BBQ pit slot has been released. You can book again anytime.</p>

        <div class="pit-info-row">
            <div class="pit-thumb" style="background-image: url('<?php echo htmlspecialchars($booking['image_path'] ?: 'images/pit-placeholder.jpg'); ?>');"></div>
            <div class="pit-info-text">
                <h2><?php echo htmlspecialchars($booking['name']); ?></h2>
                <p><?php echo htmlspecialchars($booking['location']); ?></p>
            </div>
        </div>

        <div class="booking-detail-row">
            <span class="detail-label">Date</span>
            <span class="detail-value"><?php echo htmlspecialchars($formatted_date); ?></span>
        </div>

        <div class="booking-detail-row price-row">
            <span class="detail-label">Time</span>
            <span class="detail-value"><?php echo htmlspecialchars($formatted_start . " - " . $formatted_end); ?></span>
        </div>

        <div class="confirm-actions">
            <a href="products.php" class="btn btn-accent btn-block btn-pill">Book Another Pit</a>
            <a href="my_reservations.php" class="btn btn-outline-dark btn-block btn-pill">View My Reservations</a>
        </div>

    </div>
</main>

<?php include_once("footer.php") ?>

</body>
</html>