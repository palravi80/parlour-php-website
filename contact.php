<?php
$page_title = 'Contact Us';
$page_description = 'Get in touch with Khushi Ladies Beauty Parlour for appointments and inquiries';
require_once 'includes/header.php';
require_once 'config/db.php';

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        $error_message = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Please enter a valid email address.';
    } else {
        try {
            $db = getDB();
            $stmt = $db->prepare("INSERT INTO contacts (name, email, phone, message) VALUES (:name, :email, :phone, :message)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':message', $message);

            if ($stmt->execute()) {
                $success_message = 'Thank you for contacting us! We will get back to you soon.';
                $_POST = array();
            } else {
                $error_message = 'Something went wrong. Please try again.';
            }
        } catch (PDOException $e) {
            $error_message = 'Database error. Please try again later.';
        }
    }
}
?>

<section class="page-header">
    <div class="container">
        <h1>Contact Us</h1>
        <p>We'd love to hear from you</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="contact-wrapper">
            <div class="contact-info-section">
                <h2>Get In Touch</h2>
                <p>Have questions or want to book an appointment? Reach out to us!</p>

                <div class="contact-details">
                    <div class="contact-detail-item">
                        <div class="icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="content">
                            <h4>Phone</h4>
                            <p>+91 95544 98080</p>
                        </div>
                    </div>

                    <div class="contact-detail-item">
                        <div class="icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="content">
                            <h4>Email</h4>
                            <p><?php echo ADMIN_EMAIL; ?></p>
                        </div>
                    </div>

                    <div class="contact-detail-item">
                        <div class="icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="content">
                            <h4>Address</h4>
                            <p>Akhand Nagar Road, Sarai Mohiuddinpur<br>City Jaunpur, UP 223103</p>
                        </div>
                    </div>

                    <div class="contact-detail-item">
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="content">
                            <h4>Working Hours</h4>
                            <p> Everyday: 9 AM - 8 PM </p>
                        </div>
                    </div>
                </div>

                <div class="whatsapp-btn-wrapper">
                    <a href="https://wa.me/<?php echo WHATSAPP_NUMBER; ?>" class="btn btn-whatsapp btn-lg" target="_blank">
                        <i class="fab fa-whatsapp"></i> Chat on WhatsApp
                    </a>
                </div>
            </div>

            <div class="contact-form-section">
                <h2>Send Us a Message</h2>

                <?php if ($success_message): ?>
                    <div class="alert alert-success">
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>

                <?php if ($error_message): ?>
                    <div class="alert alert-error">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" class="contact-form">
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="message">Your Message *</label>
                        <textarea id="message" name="message" rows="5" required><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="map-section">
    <div class="container-fluid">
        <iframe
            src="<?php echo GOOGLE_MAP_EMBED; ?>"
            width="100%"
            height="450"
            style="border:0;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
