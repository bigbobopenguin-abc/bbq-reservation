<?php
session_start();
require_once 'dbinfo.php';

// Must be logged in
if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

$pit_id = (int)($_POST['pit_id'] ?? 0);
$booking_date = $_POST['booking_date'] ?? '';
$start_time = $_POST['start_time'] ?? '';
$end_time = $_POST['end_time'] ?? '';
$user_id = $_SESSION['uid'];

$valid = true;

// Confirm the pit exists and is active
$stmt = $mysqli->prepare("SELECT pit_id FROM bbq_pits WHERE pit_id = ? AND status = 'active'");
$stmt->bind_param("i", $pit_id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows !== 1) {
    $valid = false;
}
$stmt->close();

// Date must not be in the past
if ($booking_date === '' || strtotime($booking_date) < strtotime(date('Y-m-d'))) {
    $valid = false;
}

// Times must be valid and end must be after start
if ($start_time === '' || $end_time === '' || strtotime($end_time) <= strtotime($start_time)) {
    $valid = false;
}

if (!$valid) {
    header("Location: reservation.php?pit_id=" . $pit_id . "&error=1");
    exit();
}

// Insert the reservation
$stmt = $mysqli->prepare(
    "INSERT INTO reservations (user_id, pit_id, booking_date, start_time, end_time, status)
     VALUES (?, ?, ?, ?, ?, 'confirmed')"
);
$stmt->bind_param("iisss", $user_id, $pit_id, $booking_date, $start_time, $end_time);

if ($stmt->execute()) {
    $reservation_id = $stmt->insert_id;
    header("Location: reservation_confirm.php?reservation_id=" . $reservation_id);
    exit();
} else {
    header("Location: reservation.php?pit_id=" . $pit_id . "&error=1");
    exit();
}
?>