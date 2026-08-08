<?php
session_start();
require_once 'dbinfo.php';

if (!isset($_SESSION['uid']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$reservation_id = (int)($_POST['reservation_id'] ?? 0);
$booking_date = $_POST['booking_date'] ?? '';
$start_time = $_POST['start_time'] ?? '';
$end_time = $_POST['end_time'] ?? '';
$status = $_POST['status'] ?? '';

$valid = true;

if ($booking_date === '' || $start_time === '' || $end_time === '') {
    $valid = false;
}

if (strtotime($end_time) <= strtotime($start_time)) {
    $valid = false;
}

if (!in_array($status, ['confirmed', 'cancelled'], true)) {
    $valid = false;
}

if (!$valid) {
    header("Location: admin_reservation_edit.php?reservation_id=" . $reservation_id . "&error=1");
    exit();
}

$stmt = $mysqli->prepare(
    "UPDATE reservations SET booking_date = ?, start_time = ?, end_time = ?, status = ? WHERE reservation_id = ?"
);
$stmt->bind_param("ssssi", $booking_date, $start_time, $end_time, $status, $reservation_id);
$stmt->execute();
$stmt->close();

header("Location: admin_reservations.php?updated=1");
exit();
?>