<?php
session_start();
require_once 'dbinfo.php';

// Must be logged in AND an admin
if (!isset($_SESSION['uid']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$action = $_POST['action'] ?? '';

if ($action === 'add') {
    $name = trim($_POST['name'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $capacity = (int)($_POST['capacity'] ?? 0);
    $price = (float)($_POST['price_per_session'] ?? 0);
    $description = trim($_POST['description'] ?? '');

    if ($name === '' || $location === '' || $capacity <= 0 || $price < 0) {
        header("Location: admin_pits.php?error=1");
        exit();
    }

    $image_path = null;

    $stmt = $mysqli->prepare(
        "INSERT INTO bbq_pits (name, description, location, capacity, price_per_session, image_path, status)
         VALUES (?, ?, ?, ?, ?, ?, 'active')"
    );
    $stmt->bind_param("sssids", $name, $description, $location, $capacity, $price, $image_path);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_pits.php?added=1");
    exit();

} elseif ($action === 'delete') {
    $pit_id = (int)($_POST['pit_id'] ?? 0);

    if ($pit_id > 0) {
        // delete image first
        $stmt = $mysqli->prepare("SELECT image_path FROM bbq_pits WHERE pit_id = ?");
        $stmt->bind_param("i", $pit_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($row && !empty($row['image_path']) && file_exists($row['image_path'])) {
            unlink($row['image_path']);
        }

        $stmt = $mysqli->prepare("DELETE FROM bbq_pits WHERE pit_id = ?");
        $stmt->bind_param("i", $pit_id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: admin_pits.php?deleted=1");
    exit();
}

header("Location: admin_pits.php");
exit();
?>