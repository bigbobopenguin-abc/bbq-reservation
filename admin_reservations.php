<?php
require_once 'admin_check.php';
require_once 'dbinfo.php';

$active_page = 'reservations';

$updated = isset($_GET['updated']);

$search = trim($_GET['search'] ?? '');
$status_filter = $_GET['status'] ?? '';
$page = max(1, (int)($_GET['page'] ?? 1));
$per_page = 10;
$offset = ($page - 1) * $per_page;


$where = "WHERE 1=1";
$params = [];
$types = "";

if ($search !== '') {
    $where .= " AND (LOWER(u.username) LIKE LOWER(?) OR LOWER(p.name) LIKE LOWER(?) OR r.reservation_id = ?)";
    $like = "%" . $search . "%";
    $params[] = $like;
    $params[] = $like;
    $params[] = (int)$search; 
    $types .= "ssi";
}

if ($status_filter !== '' && in_array($status_filter, ['confirmed', 'cancelled'], true)) {
    $where .= " AND r.status = ?";
    $params[] = $status_filter;
    $types .= "s";
}

// Count total for page
$count_sql = "SELECT COUNT(*) AS total FROM reservations r
              JOIN users u ON r.user_id = u.user_id
              JOIN bbq_pits p ON r.pit_id = p.pit_id $where";
$stmt = $mysqli->prepare($count_sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$total_rows = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();
$total_pages = max(1, ceil($total_rows / $per_page));
$sql = "SELECT r.reservation_id, r.booking_date, r.start_time, r.end_time, r.status, r.created_at,
               u.username, u.email,
               p.name AS pit_name, p.price_per_session
        FROM reservations r
        JOIN users u ON r.user_id = u.user_id
        JOIN bbq_pits p ON r.pit_id = p.pit_id
        $where
        ORDER BY r.booking_date DESC, r.start_time DESC
        LIMIT ? OFFSET ?";
$params[] = $per_page;
$params[] = $offset;
$types .= "ii";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$reservations = [];
while ($row = $result->fetch_assoc()) {
    $reservations[] = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>All Reservations - BBQ Hub Admin</title>
<link rel="stylesheet" href="css/css_reset.css">
<link rel="stylesheet" href="css/global.css">
<link rel="stylesheet" href="css/admin.css">
</head>
<body>

<?php include_once("admin_header.php") ?>

<h1 class="admin-page-title">All Reservations</h1>
<p class="admin-page-sub">View and manage reservations from all users.</p>

<?php if ($updated): ?>
    <div class="success-box">Reservation updated successfully.</div>
<?php endif; ?>

<!--  FILTER BAR  -->
<form action="admin_reservations.php" method="get" class="admin-filter-bar">
    <div class="admin-search">
        <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><path fill="currentColor" d="M10 2a8 8 0 1 0 4.9 14.32l5.39 5.39 1.41-1.41-5.39-5.39A8 8 0 0 0 10 2zm0 2a6 6 0 1 1 0 12 6 6 0 0 1 0-12z"/></svg>
        <input type="text" name="search" placeholder="Search by username, pit name, or booking ID..."
               value="<?php echo htmlspecialchars($search); ?>">
    </div>

    <select name="status" onchange="this.form.submit()">
        <option value="">All Status</option>
        <option value="confirmed" <?php echo $status_filter === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
        <option value="cancelled" <?php echo $status_filter === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
    </select>

    <button type="submit" class="btn btn-outline-dark btn-sm">Search</button>
    <a href="admin_reservations.php" class="btn btn-outline-dark btn-sm">Reset Filters</a>
</form>

<!-- TABLE  -->
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>User</th>
                <th>Pit Name</th>
                <th>Date &amp; Time</th>
                <th>Price</th>
                <th>Status</th>
                <th>Booked On</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($reservations)): ?>
                <tr><td colspan="8" class="admin-empty">No reservations found.</td></tr>
            <?php else: ?>
                <?php foreach ($reservations as $r):
                    $date_time = date("j M Y", strtotime($r['booking_date'])) . " &bull; " .
                                 date("g:i A", strtotime($r['start_time'])) . " - " . date("g:i A", strtotime($r['end_time']));
                    $booked_on = date("j M Y, g:i A", strtotime($r['created_at']));
                ?>
                    <tr>
                        <td>#<?php echo str_pad($r['reservation_id'], 6, '0', STR_PAD_LEFT); ?></td>
                        <td>
                            <div class="table-user"><?php echo htmlspecialchars($r['username']); ?></div>
                            <div class="table-sub"><?php echo htmlspecialchars($r['email']); ?></div>
                        </td>
                        <td><?php echo htmlspecialchars($r['pit_name']); ?></td>
                        <td><?php echo $date_time; ?></td>
                        <td>$<?php echo number_format($r['price_per_session'], 2); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo htmlspecialchars($r['status']); ?>">
                                <?php echo htmlspecialchars(ucfirst($r['status'])); ?>
                            </span>
                        </td>
                        <td class="table-sub"><?php echo $booked_on; ?></td>
                        <td>
                            <a href="admin_reservation_edit.php?reservation_id=<?php echo (int)$r['reservation_id']; ?>" class="btn btn-outline-dark btn-sm">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!--  PAGINATION -->
<?php if ($total_pages > 1): ?>
<div class="admin-pagination">
    <span>Showing <?php echo count($reservations); ?> of <?php echo (int)$total_rows; ?> reservations</span>
    <div class="pagination-links">
        <?php
        $query_base = "search=" . urlencode($search) . "&status=" . urlencode($status_filter);
        ?>
        <?php if ($page > 1): ?>
            <a href="?<?php echo $query_base; ?>&page=<?php echo $page - 1; ?>">&lsaquo;</a>
        <?php endif; ?>

        <?php for ($p = 1; $p <= $total_pages; $p++): ?>
            <a href="?<?php echo $query_base; ?>&page=<?php echo $p; ?>" class="<?php echo $p === $page ? 'active' : ''; ?>"><?php echo $p; ?></a>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <a href="?<?php echo $query_base; ?>&page=<?php echo $page + 1; ?>">&rsaquo;</a>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>



</body>
</html>