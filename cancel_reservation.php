<?php
session_start();
require_once 'dbinfo.php';

if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['uid'];
$reservation_id = (int)($_POST['reservation_id'] ?? 0);

// Fetch the reservation and confirm it belongs to this user
$stmt = $mysqli->prepare(
    "SELECT booking_date, start_time, status FROM reservations WHERE reservation_id = ? AND user_id = ?"
);
$stmt->bind_param("ii", $reservation_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$reservation = $result->fetch_assoc();
$stmt->close();

if (!$reservation) {
    header("Location: my_reservations.php?error=1");
    exit();
}

// Re-check the 48-hour rule server-side 
$booking_datetime = strtotime($reservation['booking_date'] . ' ' . $reservation['start_time']);
$cutoff = $booking_datetime - (48 * 3600);

if ($reservation['status'] !== 'confirmed' || time() >= $cutoff) {
    header("Location: my_reservations.php?error=1");
    exit();
}

// All checks passed - cancel it
$stmt = $mysqli->prepare("UPDATE reservations SET status = 'cancelled' WHERE reservation_id = ? AND user_id = ?");
$stmt->bind_param("ii", $reservation_id, $user_id);
$stmt->execute();
$stmt->close();

header("Location: cancel_success.php?reservation_id=" . $reservation_id);
exit();
?>