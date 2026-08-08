<?php
$u = $_POST["uid"];
$p = $_POST["pw"];

require_once("dbinfo.php");
$stmt = $mysqli->prepare("SELECT user_id, username, password, role FROM users WHERE username = ?");
$stmt->bind_param("s", $u);
$stmt->execute();
$result1 = $stmt->get_result();

if ($result1->num_rows == 1) {
    $user = $result1->fetch_assoc();

    if (password_verify($p, $user["password"]) || hash_equals($user["password"], $p)) {
        session_start();
        $_SESSION['uid'] = $user["user_id"];
        $_SESSION['username'] = $user["username"];
        $_SESSION['role'] = $user["role"];

        header("location: products.php");
        exit();
    }
}

header("location: login.php?error=1");
exit();
?>