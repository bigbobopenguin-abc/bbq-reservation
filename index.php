<?php
require_once 'dbinfo.php';

$pits = [];
$result = $mysqli->query("SELECT pit_id, name, location, capacity, price_per_session, image_path FROM bbq_pits WHERE status = 'active' LIMIT 3");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $pits[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BBQ Hub Singapore - Book Your BBQ Pit</title>
<link rel="stylesheet" href="css/css_reset.css">
<link rel="stylesheet" href="css/global.css">
<link rel="stylesheet" href="css/index.css">
</head>
<body>

<?php session_start(); include_once("header.php") ?>

<!--  HERO  -->
<section class="hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1 class="hero-title">
            BOOK YOUR<br>
            <span class="hero-title-accent">BBQ PIT</span>
        </h1>
        <p class="hero-tagline">GATHER &bull; GRILL &bull; ENJOY</p>
        <p class="hero-desc">Book your BBQ pit easily and enjoy a hassle-free outing with your family and friends.</p>

        <ul class="hero-features">
            <li>
                <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path fill="currentColor" d="M7 2v2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-2V2h-2v2H9V2H7zM5 9h14v11H5V9z"/></svg>
                <div>
                    <strong>Easy Booking</strong>
                    <span>Book online in just a few steps</span>
                </div>
            </li>
            <li>
                <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path fill="currentColor" d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5z"/></svg>
                <div>
                    <strong>Great Locations</strong>
                    <span>Scenic BBQ spots across Singapore</span>
                </div>
            </li>
            <li>
                <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path fill="currentColor" d="M16 11c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 3-1.34 3-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13zm8 0c-.29 0-.62.02-.97.05C16.16 13.9 17 15.02 17 16.5V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                <div>
                    <strong>Perfect for Any Occasion</strong>
                    <span>Family gatherings, birthday parties, celebrations &amp; more</span>
                </div>
            </li>
        </ul>

        <div class="hero-cta">
            <a href="products.php" class="btn btn-accent btn-lg">Book a Pit Now</a>
        </div>
    </div>
</section>

<!--TRUST BADGES  -->
<section class="trust-badges">
    <div class="badge-item">
        <span class="badge-icon">
            <svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true"><path fill="currentColor" d="M12 2 4 5v6c0 5 3.4 9.4 8 11 4.6-1.6 8-6 8-11V5l-8-3zm-1.2 13.4L7 11.6l1.4-1.4 2.4 2.4L15.6 8l1.4 1.4-6.2 6z"/></svg>
        </span>
        <div>
            <h4>Well Maintained</h4>
            <p>Clean and safe facilities</p>
        </div>
    </div>
    <div class="badge-item">
        <span class="badge-icon">
            <svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true"><path fill="currentColor" d="M7 2v2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-2V2h-2v2H9V2H7zM5 9h14v11H5V9z"/></svg>
        </span>
        <div>
            <h4>Easy Reservation</h4>
            <p>Book in just a few steps</p>
        </div>
    </div>
    <div class="badge-item">
        <span class="badge-icon">
            <svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true"><path fill="currentColor" d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5z"/></svg>
        </span>
        <div>
            <h4>Islandwide Locations</h4>
            <p>Parks across Singapore</p>
        </div>
    </div>
</section>

<!--  POPULAR PITS  -->
<section class="popular-pits">
    <h2>Popular BBQ Pits</h2>

    <div class="pit-grid">
        <?php if (!empty($pits)): ?>
            <?php foreach ($pits as $pit): ?>
                <a href="reservation.php?pit_id=<?php echo (int)$pit['pit_id']; ?>" class="pit-card">
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
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-pits">No BBQ pits available right now. Please check back later.</p>
        <?php endif; ?>
    </div>
</section>

<?php include_once("footer.php") ?>

</body>
</html>