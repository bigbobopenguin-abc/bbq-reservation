<?php
session_start();
require_once 'dbinfo.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $contact_number = trim($_POST['contact_number'] ?? '');
    $dob = $_POST['dob'] ?? '';
    $address = trim($_POST['address'] ?? '');
    $postal_code = trim($_POST['postal_code'] ?? '');

    // Username length
    if (strlen($username) < 3 || strlen($username) > 21) {
        $errors[] = "Username must be between 3 and 21 characters.";
    }

    // Email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    // Contact number - 8 digits
    if (!preg_match('/^\d{8}$/', $contact_number)) {
        $errors[] = "Contact number must be exactly 8 digits.";
    }

    // Password strength: 8-32 chars, at least one special char and one number
    if (!preg_match('/^(?=.*[0-9])(?=.*[\W_]).{8,32}$/', $password)) {
        $errors[] = "Password must be 8-32 characters and include at least one number and one special character.";
    }

    // Password match
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Postal code - digits only, only required if address is given
    if (!empty($address)) {
        if (!preg_match('/^\d+$/', $postal_code)) {
            $errors[] = "Postal code must contain digits only.";
        }
    } else {
        $postal_code = null; // address optional, so postal code optional too
    }

    // Date of birth - must be 18+
    if (empty($dob)) {
        $errors[] = "Date of birth is required.";
    } else {
        $dob_date = new DateTime($dob);
        $today = new DateTime();
        $age = $today->diff($dob_date)->y;
        if ($age < 18) {
            $errors[] = "You must be at least 18 years old to register.";
        }
    }

    // Uniqueness checks (only run if basic checks passed, to avoid wasted queries)
    if (empty($errors)) {
        $stmt = $mysqli->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "Username or email is already taken.";
        }
        $stmt->close();
    }

    // INSERT IF NO ERRORS 
    if (empty($errors)) {
        $stmt = $mysqli->prepare(
            "INSERT INTO users (username, email, password, contact_number, date_of_birth, address, postal_code)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("sssssss",
            $username, $email, $password_hash, $contact_number, $dob, $address, $postal_code
        );

        if ($stmt->execute()) {
            header("Location: login.php?registered=1");
            exit();
        } else {
            $errors[] = "Something went wrong. Please try again.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Account - BBQ Hub</title>
<link rel="stylesheet" href="css/css_reset.css">
<link rel="stylesheet" href="css/global.css">
<link rel="stylesheet" href="css/register.css">
</head>
<body class="auth-body">

<?php include_once("header.php") ?>

<div class="register-page">

    <!-- LEFT VISUAL PANEL -->
    <div class="register-visual">
        <div class="register-visual-overlay"></div>
        <div class="register-visual-content">
            <h1 class="register-visual-title">
                CREATE<br>
                <span class="text-accent">YOUR ACCOUNT</span>
            </h1>
            <p class="register-visual-desc">Join us and start booking BBQ pits for unforgettable gatherings.</p>

            <ul class="register-feature-list">
                <li>
                    <span class="feature-icon">
                        <svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path fill="currentColor" d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10zm0 2c-4.4 0-8 2.24-8 5v2h16v-2c0-2.76-3.6-5-8-5z"/></svg>
                    </span>
                    <div>
                        <strong>EASY BOOKING</strong>
                        <span>Book your BBQ pit in just a few steps.</span>
                    </div>
                </li>
                <li>
                    <span class="feature-icon">
                        <svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path fill="currentColor" d="M12 2 4 5v6c0 5 3.4 9.4 8 11 4.6-1.6 8-6 8-11V5l-8-3zm0 2.2 6 2.2V11c0 3.9-2.5 7.4-6 8.9-3.5-1.5-6-5-6-8.9V6.4l6-2.2z"/></svg>
                    </span>
                    <div>
                        <strong>SECURE &amp; SAFE</strong>
                        <span>Your information is always protected.</span>
                    </div>
                </li>
                <li>
                    <span class="feature-icon">
                        <svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path fill="currentColor" d="M7 2v2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-2V2h-2v2H9V2H7zM5 9h14v11H5V9z"/></svg>
                    </span>
                    <div>
                        <strong>PLAN YOUR DAY</strong>
                        <span>Choose your date, invite your friends and enjoy!</span>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <!-- RIGHT FORM PANEL  -->
    <div class="register-form-panel">
        <div class="register-form-card">

            <div class="register-form-header">
                <svg viewBox="0 0 24 24" width="34" height="34" aria-hidden="true" class="header-icon">
                    <path fill="currentColor" d="M15 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm-1 2c-3.9 0-9 1.7-9 5v3h13v-3c0-1-.4-1.9-1.1-2.6A10 10 0 0 0 14 14zm7-3v2h2v2h-2v2h-2v-2h-2v-2h2V9h2z"/>
                </svg>
                <h2>Create Account</h2>
                <p class="register-form-sub">Fill in the details below to create your free account.</p>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="error-box">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form id="registerForm" method="POST" action="register.php" novalidate>

                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-with-icon">
                        <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10zm0 2c-4.4 0-8 2.24-8 5v2h16v-2c0-2.76-3.6-5-8-5z"/></svg>
                        <input type="text" id="username" name="username" placeholder="Enter your username" required
                               value="<?php echo htmlspecialchars($username ?? ''); ?>">
                    </div>
                    <span class="field-error" id="err-username"></span>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-with-icon">
                        <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M2 5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5zm2 0 8 6 8-6H4zm16 2.4-8 6-8-6V19h16V7.4z"/></svg>
                        <input type="email" id="email" name="email" placeholder="Enter your email address" required
                               value="<?php echo htmlspecialchars($email ?? ''); ?>">
                    </div>
                    <span class="field-error" id="err-email"></span>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-with-icon">
                        <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M12 2a4 4 0 0 0-4 4v3H7a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-9a2 2 0 0 0-2-2h-1V6a4 4 0 0 0-4-4zm-2 7V6a2 2 0 1 1 4 0v3h-4z"/></svg>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        <button type="button" class="toggle-password" data-target="password" aria-label="Show password">
                            <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M12 5c-5 0-9.3 3.1-11 7 1.7 3.9 6 7 11 7s9.3-3.1 11-7c-1.7-3.9-6-7-11-7zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-2a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/></svg>
                        </button>
                    </div>
                    <span class="field-error" id="err-password"></span>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <div class="input-with-icon">
                        <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M12 2a4 4 0 0 0-4 4v3H7a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-9a2 2 0 0 0-2-2h-1V6a4 4 0 0 0-4-4zm-2 7V6a2 2 0 1 1 4 0v3h-4z"/></svg>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter your password" required>
                        <button type="button" class="toggle-password" data-target="confirm_password" aria-label="Show password">
                            <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M12 5c-5 0-9.3 3.1-11 7 1.7 3.9 6 7 11 7s9.3-3.1 11-7c-1.7-3.9-6-7-11-7zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-2a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/></svg>
                        </button>
                    </div>
                    <span class="field-error" id="err-confirm"></span>
                </div>

                <div class="form-group">
                    <label for="contact_number">Contact Number</label>
                    <div class="input-with-icon">
                        <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.6 21 3 13.4 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.4 0 .8-.2 1L6.6 10.8z"/></svg>
                        <input type="text" id="contact_number" name="contact_number" placeholder="Enter your contact number" required
                               value="<?php echo htmlspecialchars($contact_number ?? ''); ?>">
                    </div>
                    <span class="field-error" id="err-contact"></span>
                </div>

                <div class="form-group">
                    <label for="dob">Date of Birth</label>
                    <div class="input-with-icon">
                        <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M7 2v2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-2V2h-2v2H9V2H7zM5 9h14v11H5V9z"/></svg>
                        <input type="date" id="dob" name="dob" required
                               value="<?php echo htmlspecialchars($dob ?? ''); ?>">
                    </div>
                    <span class="field-error" id="err-dob"></span>
                </div>

                <div class="form-group">
                    <label for="address">Residential Address</label>
                    <textarea id="address" name="address" placeholder="Enter your residential address"><?php echo htmlspecialchars($address ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="postal_code">Postal Code</label>
                    <div class="input-with-icon">
                        <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M12 2 3 7v3c0 5.2 3.6 9.9 9 11 5.4-1.1 9-5.8 9-11V7l-9-5zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6z"/></svg>
                        <input type="text" id="postal_code" name="postal_code" placeholder="Enter your postal code"
                               value="<?php echo htmlspecialchars($postal_code ?? ''); ?>">
                    </div>
                    <span class="field-error" id="err-postal"></span>
                </div>

                <button type="submit" class="btn btn-accent btn-block">Create Account</button>

                <p class="register-login-link">Already have an account? <a href="login.php">Login</a></p>
            </form>

            <div class="register-note">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm-1 15-4-4 1.4-1.4L11 14.2l5.6-5.6L18 10l-7 7z"/></svg>
                <span>Upon successful account creation, you will be redirected to the login page.</span>
            </div>

        </div>
    </div>

</div>

<script src="js/register-validation.js"></script>
</body>
</html>