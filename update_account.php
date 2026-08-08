<?php
session_start();
require_once 'dbinfo.php';

if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['uid'];

$email = trim($_POST['email'] ?? '');
$contact_number = trim($_POST['contact_number'] ?? '');
$address = trim($_POST['address'] ?? '');
$postal_code = trim($_POST['postal_code'] ?? '');

$valid = true;

// Email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $valid = false;
}

// Contact number - 8 digits
if (!preg_match('/^\d{8}$/', $contact_number)) {
    $valid = false;
}

// Postal code - digits only, only required if address is given
if ($address !== '') {
    if (!preg_match('/^\d+$/', $postal_code)) {
        $valid = false;
    }
} else {
    $postal_code = null;
}

// Email must stay unique across accounts (excluding this user's own row)
if ($valid) {
    $stmt = $mysqli->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
    $stmt->bind_param("si", $email, $user_id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $valid = false;
    }
    $stmt->close();
}

if (!$valid) {
    header("Location: account.php?mode=edit&error=1");
    exit();
}

$stmt = $mysqli->prepare(
    "UPDATE users SET email = ?, contact_number = ?, address = ?, postal_code = ? WHERE user_id = ?"
);
$stmt->bind_param("ssssi", $email, $contact_number, $address, $postal_code, $user_id);
$stmt->execute();
$stmt->close();

header("Location: account.php?updated=1");
exit();
?>