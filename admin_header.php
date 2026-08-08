<div class="admin-topbar">
        <div class="admin-topbar-inner">
            <a href="index.php" class="admin-logo">
                <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path fill="currentColor" d="M12 2c1 3-2 4-2 7a3 3 0 0 0 6 0c0-1-.5-2-1-2.5 2 1 3 3 3 5.5a6 6 0 1 1-12 0c0-4 3-5 4-7 .5-1 1-2 2-3z"/></svg>
                BBQ Hub Admin
            </a>
            <div class="admin-user">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10zm0 2c-4.4 0-8 2.24-8 5v2h16v-2c0-2.76-3.6-5-8-5z"/></svg>
                <?php echo htmlspecialchars($_SESSION['username']); ?>
            </div>
        </div>
    </div>

    <div class="admin-layout">
        <aside class="admin-sidebar">
            <nav>
                <a href="admin_dashboard.php" class="<?php echo ($active_page ?? '') === 'dashboard' ? 'active' : ''; ?>">
                    <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M12 3 2 12h3v8h6v-6h2v6h6v-8h3z"/></svg>
                    Dashboard
                </a>
                <a href="admin_reservations.php" class="<?php echo ($active_page ?? '') === 'reservations' ? 'active' : ''; ?>">
                    <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M7 2v2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-2V2h-2v2H9V2H7zM5 9h14v11H5V9z"/></svg>
                    All Reservations
                </a>
                <a href="admin_users.php" class="<?php echo ($active_page ?? '') === 'users' ? 'active' : ''; ?>">
                    <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M16 11c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 3-1.34 3-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13zm8 0c-.29 0-.62.02-.97.05C16.16 13.9 17 15.02 17 16.5V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                    Users
                </a>
                <a href="admin_pits.php" class="<?php echo ($active_page ?? '') === 'pits' ? 'active' : ''; ?>">
                    <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M12 2 4 5v6c0 5 3.4 9.4 8 11 4.6-1.6 8-6 8-11V5l-8-3z"/></svg>
                    BBQ Pits
                </a>
                <a href="admin_reports.php" class="<?php echo ($active_page ?? '') === 'reports' ? 'active' : ''; ?>">
                    <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M4 4h16a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1zm1 2v.4l7 4.6 7-4.6V6H5zm14 2.4-6.5 4.3a1 1 0 0 1-1 0L5 8.4V18h14V8.4z"/></svg>
                    Report
                </a>
                <a href="doLogout.php">
                    <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M10 17v-2H3v-2h7V9l5 4-5 4zm9 2H12v-2h7V5h-7V3h7a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2z"/></svg>
                    Logout
                </a>
            </nav>
        </aside>

        <div class="admin-main">