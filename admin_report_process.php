<?php
session_start();
require_once 'dbinfo.php';

// Must be logged in AND an admin
if (!isset($_SESSION['uid']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$action = $_POST['action'] ?? '';
$message_id = (int)($_POST['message_id'] ?? 0);

if ($action === 'resolve' && $message_id > 0) {
    $stmt = $mysqli->prepare("UPDATE contact_messages SET status = 'resolved' WHERE message_id = ?");
    $stmt->bind_param("i", $message_id);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_reports.php?resolved=1");
    exit();

} elseif ($action === 'delete' && $message_id > 0) {
    $stmt = $mysqli->prepare("DELETE FROM contact_messages WHERE message_id = ?");
    $stmt->bind_param("i", $message_id);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_reports.php?deleted=1");
    exit();
}

header("Location: admin_reports.php");
exit();
?>