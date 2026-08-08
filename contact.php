<?php
session_start();

$sent = isset($_GET['sent']);
$errors = [];
if (isset($_GET['error'])) {
    $errors[] = "Please fill in all fields with a valid email address.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us - BBQ Hub</title>
<link rel="stylesheet" href="css/css_reset.css">
<link rel="stylesheet" href="css/global.css">
<link rel="stylesheet" href="css/contact.css">
</head>
<body>

<?php include_once("header.php") ?>

<section class="contact-intro">
    <h1>Get In Touch</h1>
    <p>Questions about a booking, a pit, or anything else? We'd love to hear from you.</p>
</section>

<main id="main">
    <div class="contact-wrap">

        <!-- CONTACT FORM  -->
        <div class="contact-form-card">
            <h2>Send Us a Message</h2>

            <?php if ($sent): ?>
                <div class="success-box">Thanks for reaching out! We'll get back to you shortly.</div>
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

            <form id="contactForm" action="contact_process.php" method="POST" novalidate>
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter your name" required>
                    <span class="field-error" id="err-name"></span>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    <span class="field-error" id="err-email"></span>
                </div>

                <div class="form-group">
                    <label for="subject">Subject</label>
                    <select id="subject" name="subject" required>
                        <option value="">Select an enquiry type</option>
                        <option value="General Enquiry">General Enquiry</option>
                        <option value="Booking Enquiry">Booking Enquiry</option>
                        <option value="Cancellation / Refund">Cancellation / Refund</option>
                        <option value="Facility Issue / Feedback">Facility Issue / Feedback</option>
                        <option value="Account / Login Issue">Account / Login Issue</option>
                        <option value="Partnership / Others">Partnership / Others</option>
                    </select>
                    <span class="field-error" id="err-subject"></span>
                </div>

                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" placeholder="Tell us more..." required></textarea>
                    <span class="field-error" id="err-message"></span>
                </div>

                <button type="submit" class="btn btn-accent btn-block">Send Message</button>
            </form>
        </div>

        <!-- CONTACT INFO -->
        <div class="contact-info">
            <div class="contact-info-item">
                <span class="contact-icon">
                    <svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path fill="currentColor" d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5z"/></svg>
                </span>
                <div>
                    <h3>Our Office</h3>
                    <p>1 Grill Street, #03-01<br>Singapore 123456</p>
                </div>
            </div>

            <div class="contact-info-item">
                <span class="contact-icon">
                    <svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path fill="currentColor" d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.6 21 3 13.4 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.4 0 .8-.2 1L6.6 10.8z"/></svg>
                </span>
                <div>
                    <h3>Phone</h3>
                    <p>+65 6123 4567</p>
                </div>
            </div>

            <div class="contact-info-item">
                <span class="contact-icon">
                    <svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path fill="currentColor" d="M2 5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5zm2 0 8 6 8-6H4zm16 2.4-8 6-8-6V19h16V7.4z"/></svg>
                </span>
                <div>
                    <h3>Email</h3>
                    <p>hello@bbqhub.sg</p>
                </div>
            </div>

            <div class="contact-info-item">
                <span class="contact-icon">
                    <svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path fill="currentColor" d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm1 10.6 4.2 2.5-.7 1.2L11 13V6h1.5v6.6z"/></svg>
                </span>
                <div>
                    <h3>Support Hours</h3>
                    <p>Mon - Fri, 9am - 6pm</p>
                </div>
            </div>
        </div>

    </div>
</main>

<?php include_once("footer.php") ?>

<script src="js/contact-validation.js"></script>
</body>
</html>