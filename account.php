<?php
require_once 'check_session.php';
require_once 'dbinfo.php';

$user_id = $_SESSION['uid'];
$mode = ($_GET['mode'] ?? 'view') === 'edit' ? 'edit' : 'view';

// Fetch current account details
$stmt = $mysqli->prepare("SELECT username, email, contact_number, address, postal_code, date_of_birth FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$updated = isset($_GET['updated']);
$errors = [];
if (isset($_GET['error'])) {
    $errors[] = "Please check your details - make sure your email is valid, contact number is 8 digits, and postal code is digits only.";
}

$formatted_dob = date("d / m / Y", strtotime($user['date_of_birth']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Account - BBQ Hub</title>
<link rel="stylesheet" href="css/css_reset.css">
<link rel="stylesheet" href="css/global.css">
<link rel="stylesheet" href="css/account.css">
</head>
<body>

<?php include_once("header.php") ?>

<main id="main">
    <div class="account-page">

        <!--  SIDEBAR -->
        <aside class="account-sidebar">
            <nav>
                <a href="index.php">
                    <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M12 3 2 12h3v8h6v-6h2v6h6v-8h3z"/></svg>
                    Dashboard
                </a>
                <a href="my_reservations.php">
                    <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M7 2v2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-2V2h-2v2H9V2H7zM5 9h14v11H5V9z"/></svg>
                    My Bookings
                </a>
                <a href="account.php" class="active">
                    <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10zm0 2c-4.4 0-8 2.24-8 5v2h16v-2c0-2.76-3.6-5-8-5z"/></svg>
                    My Account
                </a>
                <a href="doLogout.php">
                    <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M10 17v-2H3v-2h7V9l5 4-5 4zm9 2H12v-2h7V5h-7V3h7a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2z"/></svg>
                    Logout
                </a>
            </nav>
        </aside>

        <!-- MAIN PANEL  -->
        <div class="account-main">

            <?php if ($updated): ?>
                <div class="success-box">Your account details have been updated.</div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="error-box">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if ($mode === 'view'): ?>

                <!--  VIEW MODE -->
                <h1>My Account</h1>
                <p class="account-subtitle">View and manage your account details.</p>

                <div class="account-card">
                    <div class="account-field">
                        <span class="field-label">Username</span>
                        <span class="field-value"><?php echo htmlspecialchars($user['username']); ?></span>
                    </div>
                    <div class="account-field">
                        <span class="field-label">Email Address</span>
                        <span class="field-value"><?php echo htmlspecialchars($user['email']); ?></span>
                    </div>
                    <div class="account-field">
                        <span class="field-label">Contact Number</span>
                        <span class="field-value"><?php echo htmlspecialchars($user['contact_number']); ?></span>
                    </div>
                    <div class="account-field">
                        <span class="field-label">Residential Address</span>
                        <span class="field-value"><?php echo nl2br(htmlspecialchars($user['address'] ?: '-')); ?></span>
                    </div>
                    <div class="account-field">
                        <span class="field-label">Postal Code</span>
                        <span class="field-value"><?php echo htmlspecialchars($user['postal_code'] ?: '-'); ?></span>
                    </div>
                    <div class="account-field no-border">
                        <span class="field-label">Date of Birth</span>
                        <span class="field-value"><?php echo htmlspecialchars($formatted_dob); ?></span>
                    </div>

                    <a href="account.php?mode=edit" class="btn btn-outline-dark btn-block">
                        <svg viewBox="0 0 24 24" width="14" height="14" aria-hidden="true" style="vertical-align:-2px; margin-right:6px;"><path fill="currentColor" d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                        Update Account
                    </a>
                </div>

                <div class="account-note">
                    <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><path fill="currentColor" d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    <div>
                        <strong>Users can only update the following fields:</strong>
                        <ul>
                            <li>Email Address</li>
                            <li>Contact Number</li>
                            <li>Residential Address</li>
                            <li>Postal Code</li>
                        </ul>
                    </div>
                </div>

            <?php else: ?>

                <!--  EDIT MODE  -->
                <a href="account.php" class="back-link">
                    <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><path fill="currentColor" d="M15.4 7.4 14 6l-6 6 6 6 1.4-1.4L10.8 12z"/></svg>
                    Back to My Account
                </a>

                <h1>Update Account</h1>
                <p class="account-subtitle">Update your account information.</p>

                <div class="account-card">
                    <form action="update_account.php" method="POST" novalidate>

                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" required
                                   value="<?php echo htmlspecialchars($user['email']); ?>">
                        </div>

                        <div class="form-group">
                            <label for="contact_number">Contact Number *</label>
                            <input type="text" id="contact_number" name="contact_number" required
                                   value="<?php echo htmlspecialchars($user['contact_number']); ?>">
                        </div>

                        <div class="form-group">
                            <label for="address">Residential Address *</label>
                            <textarea id="address" name="address"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="postal_code">Postal Code *</label>
                            <input type="text" id="postal_code" name="postal_code"
                                   value="<?php echo htmlspecialchars($user['postal_code'] ?? ''); ?>">
                        </div>

                        <div class="edit-actions">
                            <button type="submit" class="btn btn-accent">Save Changes</button>
                            <a href="account.php" class="btn btn-outline-dark">Cancel</a>
                        </div>
                    </form>
                </div>

                <div class="account-note">
                    <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><path fill="currentColor" d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    <span>Only the fields shown above can be updated. Other information such as Username and Date of Birth cannot be changed.</span>
                </div>

            <?php endif; ?>

        </div>
    </div>
</main>
</body>
</html>