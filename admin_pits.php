<?php
require_once 'admin_check.php';
require_once 'dbinfo.php';

$active_page = 'pits';

$errors = [];
if (isset($_GET['error'])) {
    $errors[] = "Please fill in all fields correctly (capacity and price must be numbers).";
}
$added = isset($_GET['added']);
$deleted = isset($_GET['deleted']);

$result = $mysqli->query("SELECT pit_id, name, location, capacity, price_per_session, status, image_path FROM bbq_pits ORDER BY pit_id DESC");
$pits = [];
while ($row = $result->fetch_assoc()) {
    $pits[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BBQ Pits - BBQ Hub Admin</title>
<link rel="stylesheet" href="css/css_reset.css">
<link rel="stylesheet" href="css/global.css">
<link rel="stylesheet" href="css/admin.css">
</head>
<body>

<?php include_once("admin_header.php") ?>

<h1 class="admin-page-title">BBQ Pits</h1>
<p class="admin-page-sub">Add new pits or remove existing ones from the master list.</p>

<?php if ($added): ?>
    <div class="success-box">New BBQ pit added.</div>
<?php endif; ?>
<?php if ($deleted): ?>
    <div class="success-box">BBQ pit removed.</div>
<?php endif; ?>
<?php if (!empty($errors)): ?>
    <div class="error-box">
        <ul><?php foreach ($errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?></ul>
    </div>
<?php endif; ?>

<!-- ADD NEW PIT  -->
<div class="admin-card">
    <h2>Add New BBQ Pit</h2>
    <form action="admin_pit_process.php" method="POST" enctype="multipart/form-data" class="admin-add-form">
        <input type="hidden" name="action" value="add">

        <div class="form-group">
            <label for="name">Pit Name</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" id="location" name="location" required>
        </div>
        <div class="form-group">
            <label for="capacity">Capacity</label>
            <input type="number" id="capacity" name="capacity" min="1" required>
        </div>
        <div class="form-group">
            <label for="price_per_session">Price / Day ($)</label>
            <input type="number" id="price_per_session" name="price_per_session" min="0" step="0.01" required>
        </div>
        <div class="form-group form-group-wide">
            <label for="description">Description</label>
            <textarea id="description" name="description"></textarea>
        </div>


        <button type="submit" class="btn btn-accent">Add Pit</button>
    </form>
</div>

<!-- EXISTING PITS TABLE  -->
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Photo</th>
                <th>Name</th>
                <th>Location</th>
                <th>Capacity</th>
                <th>Price / Day</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($pits)): ?>
                <tr><td colspan="7" class="admin-empty">No BBQ pits yet.</td></tr>
            <?php else: ?>
                <?php foreach ($pits as $p): ?>
                    <tr>
                        <td>
                            <div class="pit-thumb-sm" style="background-image: url('<?php echo htmlspecialchars($p['image_path'] ?: 'images/pit-placeholder.jpg'); ?>');"></div>
                        </td>
                        <td><?php echo htmlspecialchars($p['name']); ?></td>
                        <td><?php echo htmlspecialchars($p['location']); ?></td>
                        <td><?php echo (int)$p['capacity']; ?> people</td>
                        <td>$<?php echo number_format($p['price_per_session'], 2); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo $p['status'] === 'active' ? 'confirmed' : 'cancelled'; ?>">
                                <?php echo htmlspecialchars(ucfirst($p['status'])); ?>
                            </span>
                        </td>
                        <td>
                            <form action="admin_pit_process.php" method="POST" onsubmit="return confirm('Delete this pit? This cannot be undone.');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="pit_id" value="<?php echo (int)$p['pit_id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>


</body>
</html>