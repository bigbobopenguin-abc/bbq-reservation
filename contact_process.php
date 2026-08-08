<?php
require_once 'dbinfo.php';

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

$allowed_subjects = [
    "General Enquiry",
    "Booking Enquiry",
    "Cancellation / Refund",
    "Facility Issue / Feedback",
    "Account / Login Issue",
    "Partnership / Others"
];

$valid = true;

if ($name === '' || $message === '') {
    $valid = false;
}

if (!in_array($subject, $allowed_subjects, true)) {
    $valid = false;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $valid = false;
}

if (!$valid) {
    header("Location: contact.php?error=1");
    exit();
}

$stmt = $mysqli->prepare(
    "INSERT INTO contact_messages (name, email, subject, message, status)
     VALUES (?, ?, ?, ?, 'new')"
);
$stmt->bind_param("ssss", $name, $email, $subject, $message);
$stmt->execute();
$stmt->close();

header("Location: contact.php?sent=1");
exit();
?>