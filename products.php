<?php
session_start();
require_once 'dbinfo.php';

// Read filter/search/sort inputs 
$search = trim($_GET['search'] ?? '');
$location = trim($_GET['location'] ?? '');
$sort = $_GET['sort'] ?? 'price_asc';

// Build the query dynamically 
$sql = "SELECT pit_id, name, location, capacity, price_per_session, image_path
        FROM bbq_pits
        WHERE status = 'active'";
$params = [];
$types = "";

// Case-insensitive search by name
if ($search !== '') {
    $sql .= " AND LOWER(name) LIKE LOWER(?)";
    $params[] = "%" . $search . "%";
    $types .= "s";
}

// Location filter
if ($location !== '') {
    $sql .= " AND location = ?";
    $params[] = $location;
    $types .= "s";
}

// Sorting
switch ($sort) {
    case 'price_desc':
        $sql .= " ORDER BY price_per_session DESC";
        break;
    case 'name_asc':
        $sql .= " ORDER BY name ASC";
        break;
    case 'price_asc':
    default:
        $sql .= " ORDER BY price_per_session ASC";
        break;
}

$stmt = $mysqli->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$pits = [];
while ($row = $result->fetch_assoc()) {
    $pits[] = $row;
}
$stmt->close();

// Get distinct locations for the filter dropdown 
$locations = [];
$locResult = $mysqli->query("SELECT DISTINCT location FROM bbq_pits WHERE status = 'active' ORDER BY location");
if ($locResult) {
    while ($row = $locResult->fetch_assoc()) {
        $locations[] = $row['location'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BBQ Pit Listings - BBQ Hub</title>
<link rel="stylesheet" href="css/css_reset.css">
<link rel="stylesheet" href="css/global.css">
<link rel="stylesheet" href="css/products.css">
</head>
<body>

<?php include_once("header.php") ?>

<!-- PAGE TITLE + SEARCH -->
<section class="listings-intro">
    <h1 class="page-title">BBQ Pit Listings</h1>

    <form action="products.php" method="get" class="search-bar">
        <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" class="search-icon"><path fill="currentColor" d="M10 2a8 8 0 1 0 4.9 14.32l5.39 5.39 1.41-1.41-5.39-5.39A8 8 0 0 0 10 2zm0 2a6 6 0 1 1 0 12 6 6 0 0 1 0-12z"/></svg>
        <input type="text" name="search" placeholder="Search BBQ pit by name or location"
               value="<?php echo htmlspecialchars($search); ?>">
        <?php if ($location !== ''): ?>
            <input type="hidden" name="location" value="<?php echo htmlspecialchars($location); ?>">
        <?php endif; ?>
        <?php if ($sort !== 'price_asc'): ?>
            <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort); ?>">
        <?php endif; ?>
        <button type="submit" class="search-submit">Search</button>
    </form>
</section>

<main id="main">
    <form action="products.php" method="get" class="filter-bar">
        <?php if ($search !== ''): ?>
            <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
        <?php endif; ?>

        <div class="filter-group">
            <label for="location">Location</label>
            <select name="location" id="location" onchange="this.form.submit()">
                <option value="">All Location</option>
                <?php foreach ($locations as $loc): ?>
                    <option value="<?php echo htmlspecialchars($loc); ?>" <?php echo ($location === $loc) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($loc); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filter-group">
            <label for="sort">Sort By</label>
            <select name="sort" id="sort" onchange="this.form.submit()">
                <option value="price_asc" <?php echo ($sort === 'price_asc') ? 'selected' : ''; ?>>Price: Low to High</option>
                <option value="price_desc" <?php echo ($sort === 'price_desc') ? 'selected' : ''; ?>>Price: High to Low</option>
                <option value="name_asc" <?php echo ($sort === 'name_asc') ? 'selected' : ''; ?>>Name: A to Z</option>
            </select>
        </div>
    </form>

    <!--  PIT LISTING GRID  -->
    <div class="pit-grid">
        <?php if (!empty($pits)): ?>
            <?php foreach ($pits as $pit): ?>
                <div class="pit-card">
                    <div class="pit-card-image" style="background-image: url('<?php echo htmlspecialchars($pit['image_path'] ?: 'images/pit-placeholder.jpg'); ?>');"></div>
                    <div class="pit-card-body">
                        <h3><?php echo htmlspecialchars($pit['name']); ?></h3>
                        <p class="pit-capacity">Capacity <?php echo (int)$pit['capacity']; ?> People</p>
                        <div class="pit-card-footer">
                            <span class="pit-location">
                                <svg viewBox="0 0 24 24" width="14" height="14" aria-hidden="true"><path fill="currentColor" d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5z"/></svg>
                                <?php echo htmlspecialchars($pit['location']); ?>
                            </span>
                            <span class="pit-price">$<?php echo number_format($pit['price_per_session'], 0); ?>/day</span>
                        </div>
                        <a href="reservation.php?pit_id=<?php echo (int)$pit['pit_id']; ?>" class="btn btn-accent pit-reserve-btn">Reserve Now</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-pits">No BBQ pits match your search. Try a different keyword or filter.</p>
        <?php endif; ?>
    </div>
</main>

<?php include_once("footer.php") ?>

</body>
</html>