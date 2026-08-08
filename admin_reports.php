<?php
require_once 'admin_check.php';
require_once 'dbinfo.php';

$active_page = 'reports';

$resolved = isset($_GET['resolved']);
$deleted = isset($_GET['deleted']);

$result = $mysqli->query(
    "SELECT message_id, name, email, subject, message, status, created_at FROM contact_messages ORDER BY created_at DESC"
);
$reports = [];
while ($row = $result->fetch_assoc()) {
    $reports[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Report - BBQ Hub Admin</title>
<link rel="stylesheet" href="css/css_reset.css">
<link rel="stylesheet" href="css/global.css">
<link rel="stylesheet" href="css/admin.css">
</head>
<body>

<?php include_once("admin_header.php") ?>

<h1 class="admin-page-title">Report</h1>
<p class="admin-page-sub">Contact Us messages submitted by users.</p>

<?php if ($resolved): ?>
    <div class="success-box">Message marked as resolved.</div>
<?php endif; ?>
<?php if ($deleted): ?>
    <div class="success-box">Message removed.</div>
<?php endif; ?>

<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Status</th>
                <th>Received</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($reports)): ?>
                <tr><td colspan="7" class="admin-empty">No messages yet.</td></tr>
            <?php else: ?>
                <?php foreach ($reports as $r): ?>
                    <tr>
                        <td class="table-user"><?php echo htmlspecialchars($r['name']); ?></td>
                        <td><?php echo htmlspecialchars($r['email']); ?></td>
                        <td><?php echo htmlspecialchars($r['subject']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($r['message'])); ?></td>
                        <td>
                            <span class="status-badge <?php echo $r['status'] === 'resolved' ? 'status-confirmed' : 'status-cancelled'; ?>">
                                <?php echo htmlspecialchars(ucfirst($r['status'])); ?>
                            </span>
                        </td>
                        <td class="table-sub"><?php echo date("j M Y, g:ia", strtotime($r['created_at'])); ?></td>
                        <td>
                            <div class="edit-actions">
                                <?php if ($r['status'] !== 'resolved'): ?>
                                    <form action="admin_report_process.php" method="POST">
                                        <input type="hidden" name="action" value="resolve">
                                        <input type="hidden" name="message_id" value="<?php echo (int)$r['message_id']; ?>">
                                        <button type="submit" class="btn btn-accent btn-sm">Resolve</button>
                                    </form>
                                <?php endif; ?>
                                <form action="admin_report_process.php" method="POST" onsubmit="return confirm('Delete this message? This cannot be undone.');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="message_id" value="<?php echo (int)$r['message_id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>


</body>
</html>