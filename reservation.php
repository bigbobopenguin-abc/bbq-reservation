<?php
require_once 'check_session.php'; 
require_once 'dbinfo.php';

$pit_id = (int)($_GET['pit_id'] ?? 0);

$stmt = $mysqli->prepare("SELECT pit_id, name, description, location, capacity, price_per_session, image_path FROM bbq_pits WHERE pit_id = ? AND status = 'active'");
$stmt->bind_param("i", $pit_id);
$stmt->execute();
$result = $stmt->get_result();
$pit = $result->fetch_assoc();
$stmt->close();

// If the pit doesn't exist (or was removed), send the user back to the listing
if (!$pit) {
    header("Location: products.php");
    exit();
}

$errors = [];
if (isset($_GET['error'])) {
    $errors[] = "Please check your reservation details and try again. Make sure your date is not in the past and your end time is after your start time.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reserve <?php echo htmlspecialchars($pit['name']); ?> - BBQ Hub</title>
<link rel="stylesheet" href="css/css_reset.css">
<link rel="stylesheet" href="css/global.css">
<link rel="stylesheet" href="css/reservation.css">
</head>
<body>

<?php include_once("header.php") ?>

<main id="main">
    <div class="booking-card">

        <div class="booking-card-header">
            <a href="products.php" class="back-btn" aria-label="Back to listings">
                <svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path fill="currentColor" d="M15.4 7.4 14 6l-6 6 6 6 1.4-1.4L10.8 12z"/></svg>
            </a>
            <h1>Your Booking</h1>
        </div>

        <div class="pit-info-row">
            <div class="pit-thumb" style="background-image: url('<?php echo htmlspecialchars($pit['image_path'] ?: 'images/pit-placeholder.jpg'); ?>');"></div>
            <div class="pit-info-text">
                <h2><?php echo htmlspecialchars($pit['name']); ?></h2>
                <p><?php echo htmlspecialchars($pit['location']); ?></p>
            </div>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="error-box">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form id="reservationForm" action="reservation_process.php" method="POST" novalidate>
            <input type="hidden" name="pit_id" value="<?php echo (int)$pit['pit_id']; ?>">

            <div class="booking-detail-row">
                <span class="detail-label">Capacity</span>
                <span class="detail-value">
                    <svg viewBox="0 0 24 24" width="15" height="15" aria-hidden="true"><path fill="currentColor" d="M16 11c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 3-1.34 3-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13zm8 0c-.29 0-.62.02-.97.05C16.16 13.9 17 15.02 17 16.5V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                    <?php echo (int)$pit['capacity']; ?> People
                </span>
            </div>

            <p class="section-label">Choose Date &amp; Time</p>

            <div class="pill-field">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M7 2v2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-2V2h-2v2H9V2H7zM5 9h14v11H5V9z"/></svg>
                <input type="date" id="booking_date" name="booking_date" required>
            </div>
            <span class="field-error" id="err-date"></span>

            <div class="pill-field-row">
                <div class="pill-field">
                    <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm1 10.6 4.2 2.5-.7 1.2L11 13V6h1.5v6.6z"/></svg>
                    <input type="time" id="start_time" name="start_time" required>
                </div>
                <span class="pill-field-sep">&ndash;</span>
                <div class="pill-field">
                    <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm1 10.6 4.2 2.5-.7 1.2L11 13V6h1.5v6.6z"/></svg>
                    <input type="time" id="end_time" name="end_time" required>
                </div>
            </div>
            <span class="field-error" id="err-time"></span>

            <div class="booking-detail-row price-row">
                <span class="detail-label">Price</span>
                <span class="detail-value price">$<?php echo number_format($pit['price_per_session'], 2); ?> <span class="unit">/ day</span></span>
            </div>

            <button type="submit" class="btn btn-accent btn-block btn-pill">Reserve Now</button>
        </form>

    </div>
</main>

<?php include_once("footer.php") ?>

<script src="js/reservation-validation.js"></script>
</body>
</html>