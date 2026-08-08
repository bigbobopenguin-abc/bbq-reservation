<header>
        <div class="header-inner">
            <a href="index.php" class="logo-link">
                <svg class="logo-flame" viewBox="0 0 24 24" width="26" height="26" aria-hidden="true">
                    <path fill="currentColor" d="M12 2c1 3-2 4-2 7a3 3 0 0 0 6 0c0-1-.5-2-1-2.5 2 1 3 3 3 5.5a6 6 0 1 1-12 0c0-4 3-5 4-7 .5-1 1-2 2-3z"/>
                </svg>
                <span class="logo-text">BBQ<span class="logo-accent">Hub</span></span>
            </a>

            <nav>
                <label for="hamburger">&#9776;</label>
                <input type="checkbox" id="hamburger" />
                <ul id="menu">
    <li><a href="index.php">Home</a></li>
    <li><a href="products.php">Pits</a></li>

    <?php if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'): ?>
        <li><a href="contact.php">Contact</a></li>
    <?php endif; ?>

    <?php if (isset($_SESSION['uid'])): ?>

        <li class="menu-only"><a href="account.php">My Account</a></li>

        <?php if ($_SESSION['role'] !== 'admin'): ?>
            <li class="menu-only"><a href="my_reservations.php">My Reservations</a></li>
        <?php endif; ?>

        <?php if ($_SESSION['role'] === 'admin'): ?>
            <li class="menu-only"><a href="admin_dashboard.php">Admin</a></li>
        <?php endif; ?>

    <?php endif; ?>
</ul>
            </nav>

            <div class="header-actions">
                <?php if (isset($_SESSION['uid'])): ?>
                    <a href="account.php" class="header-greeting">Hi, <?php echo htmlspecialchars($_SESSION['username']); ?></a>
                    <a href="doLogout.php" class="btn btn-outline">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline">Login</a>
                    <a href="register.php" class="btn btn-accent">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </header>