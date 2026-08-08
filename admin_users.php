<?php
require_once 'admin_check.php';
require_once 'dbinfo.php';

$active_page = 'users';

$result = $mysqli->query(
    "SELECT user_id, username, email, contact_number, role, created_at FROM users ORDER BY created_at DESC"
);
$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Users - BBQ Hub Admin</title>
<link rel="stylesheet" href="css/css_reset.css">
<link rel="stylesheet" href="css/global.css">
<link rel="stylesheet" href="css/admin.css">
</head>
<body>

<?php include_once("admin_header.php") ?>

<h1 class="admin-page-title">Users</h1>
<p class="admin-page-sub">All registered accounts on BBQ Hub.</p>

<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Role</th>
                <th>Joined</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($users)): ?>
                <tr><td colspan="5" class="admin-empty">No users found.</td></tr>
            <?php else: ?>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($u['username']); ?></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td><?php echo htmlspecialchars($u['contact_number']); ?></td>
                        <td>
                            <span class="role-badge role-<?php echo htmlspecialchars($u['role']); ?>">
                                <?php echo htmlspecialchars(ucfirst($u['role'])); ?>
                            </span>
                        </td>
                        <td class="table-sub"><?php echo date("j M Y", strtotime($u['created_at'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>


</body>
</html>