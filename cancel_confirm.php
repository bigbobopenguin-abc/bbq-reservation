<?php
require_once 'check_session.php';
require_once 'dbinfo.php';

$user_id = $_SESSION['uid'];
$reservation_id = (int)($_GET['reservation_id'] ?? 0);

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

// Doesn't exist or doesn't belong to this user
if (!$booking) {
    header("Location: my_reservations.php");
    exit();
}

// Re-check the 48-hour rule 
$booking_datetime = strtotime($booking['booking_date'] . ' ' . $booking['start_time']);
$cutoff = $booking_datetime - (48 * 3600);
$can_cancel = ($booking['status'] === 'confirmed' && time() < $cutoff);

if (!$can_cancel) {
    header("Location: my_reservations.php?error=1");
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
<title>Cancel Reservation - BBQ Hub</title>
<link rel="stylesheet" href="css/css_reset.css">
<link rel="stylesheet" href="css/global.css">
<link rel="stylesheet" href="css/reservation.css">
<link rel="stylesheet" href="css/confirm.css">
</head>
<body>

<?php include_once("header.php") ?>

<main id="main">
    <div class="booking-card confirm-card">

        <div class="confirm-icon confirm-icon-danger">
            <svg viewBox="0 0 24 24" width="32" height="32" aria-hidden="true"><path fill="currentColor" d="M12 2 1 21h22L12 2zm0 6 6.5 11h-13L12 8zm-1 3v4h2v-4h-2zm0 5v2h2v-2h-2z"/></svg>
        </div>

        <h1 class="confirm-title">Cancel this reservation?</h1>
        <p class="confirm-sub">This can't be undone. Your pit will be released for others to book.</p>

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
            <form action="cancel_reservation.php" method="POST">
                <input type="hidden" name="reservation_id" value="<?php echo (int)$booking['reservation_id']; ?>">
                <button type="submit" class="btn btn-danger btn-block btn-pill">Yes, Cancel Reservation</button>
            </form>
            <a href="my_reservations.php" class="btn btn-outline-dark btn-block btn-pill">No, Keep My Reservation</a>
        </div>

    </div>
</main>

<?php include_once("footer.php") ?>

</body>
</html>