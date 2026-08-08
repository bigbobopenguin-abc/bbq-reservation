<?php
require_once 'check_session.php';
require_once 'dbinfo.php';

$user_id = $_SESSION['uid'];

$stmt = $mysqli->prepare(
    "SELECT r.reservation_id, r.booking_date, r.start_time, r.end_time, r.status,
            p.name, p.location, p.image_path, p.price_per_session
     FROM reservations r
     JOIN bbq_pits p ON r.pit_id = p.pit_id
     WHERE r.user_id = ?
     ORDER BY r.booking_date DESC, r.start_time DESC"
);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$reservations = [];
while ($row = $result->fetch_assoc()) {
    $reservations[] = $row;
}
$stmt->close();

$cancelled = isset($_GET['cancelled']);
$cancel_error = isset($_GET['error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Reservations - BBQ Hub</title>
<link rel="stylesheet" href="css/css_reset.css">
<link rel="stylesheet" href="css/global.css">
<link rel="stylesheet" href="css/my_reservations.css">
</head>
<body>

<?php include_once("header.php") ?>

<main id="main">
    <div class="reservations-wrap">
        <h1 class="page-title">My Reservations</h1>

        <?php if ($cancelled): ?>
            <div class="success-box">Your reservation has been cancelled.</div>
        <?php endif; ?>

        <?php if ($cancel_error): ?>
            <div class="error-box">That reservation can't be cancelled - it must be cancelled at least 48 hours before the start time.</div>
        <?php endif; ?>

        <?php if (empty($reservations)): ?>
            <div class="empty-state">
                <p>You don't have any reservations yet.</p>
                <a href="products.php" class="btn btn-accent">Browse BBQ Pits</a>
            </div>
        <?php else: ?>
            <div class="reservation-list">
                <?php foreach ($reservations as $r):
                    $booking_datetime = strtotime($r['booking_date'] . ' ' . $r['start_time']);
                    $cutoff = $booking_datetime - (48 * 3600); // 48 hours before start
                    $can_cancel = ($r['status'] === 'confirmed' && time() < $cutoff);

                    $formatted_date = date("j M Y", strtotime($r['booking_date']));
                    $formatted_start = date("g:i A", strtotime($r['start_time']));
                    $formatted_end = date("g:i A", strtotime($r['end_time']));
                ?>
                    <div class="reservation-card">
                        <div class="reservation-thumb" style="background-image: url('<?php echo htmlspecialchars($r['image_path'] ?: 'images/pit-placeholder.jpg'); ?>');"></div>

                        <div class="reservation-info">
                            <div class="reservation-info-top">
                                <h2><?php echo htmlspecialchars($r['name']); ?></h2>
                                <span class="status-badge status-<?php echo htmlspecialchars($r['status']); ?>">
                                    <?php echo htmlspecialchars(ucfirst($r['status'])); ?>
                                </span>
                            </div>
                            <p class="reservation-location">
                                <svg viewBox="0 0 24 24" width="13" height="13" aria-hidden="true"><path fill="currentColor" d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5z"/></svg>
                                <?php echo htmlspecialchars($r['location']); ?>
                            </p>
                            <p class="reservation-datetime">
                                <?php echo htmlspecialchars($formatted_date); ?> &bull; <?php echo htmlspecialchars($formatted_start . " - " . $formatted_end); ?>
                            </p>
                            <p class="reservation-price">$<?php echo number_format($r['price_per_session'], 2); ?> / day</p>
                        </div>

                        <div class="reservation-action">
                            <?php if ($can_cancel): ?>
                                <a href="cancel_confirm.php?reservation_id=<?php echo (int)$r['reservation_id']; ?>" class="btn btn-outline-dark btn-sm">Cancel</a>
                            <?php elseif ($r['status'] === 'confirmed'): ?>
                                <span class="cannot-cancel">Cannot cancel<br><small>within 48 hrs of start</small></span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include_once("footer.php") ?>

</body>
</html>