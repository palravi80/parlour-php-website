<?php
$page_title = 'Our Services';
$page_description = 'Explore our wide range of beauty services at Khushee Ladies Beauty Parlour';
require_once 'includes/header.php';
require_once 'config/db.php';

$db = getDB();

$category_filter = isset($_GET['category']) ? $_GET['category'] : 'all';

$sql = "SELECT * FROM services WHERE is_active = 1";
$stmt = $db->prepare($sql);
$stmt->execute();
$services = $stmt->fetchAll();
?>

<section class="page-header">
    <div class="container">
        <h1>Our Services</h1>
        <p>Discover our comprehensive range of beauty treatments</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="services-grid">
            <?php if (count($services) > 0): ?>
                <?php foreach ($services as $service): ?>
                    <div class="service-card">
                        <?php if ($service['image']): ?>
                            <div class="service-image">
                                <img src="<?php echo UPLOAD_URL . $service['image']; ?>" alt="<?php echo htmlspecialchars($service['title']); ?>">
                            </div>
                        <?php endif; ?>
                        <div class="service-content">
                            <h3><?php echo htmlspecialchars($service['title']); ?></h3>
                            <p><?php echo nl2br(htmlspecialchars($service['description'])); ?></p>
                            <div class="service-meta">
                                <span class="price">₹<?php echo number_format($service['price'], 2); ?></span>
                                <?php if ($service['duration']): ?>
                                    <span class="duration"><i class="far fa-clock"></i> <?php echo htmlspecialchars($service['duration']); ?></span>
                                <?php endif; ?>
                            </div>
                            <a href="contact.php" class="btn btn-primary btn-sm">Book Now</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-results">
                    <p>No services available at the moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="section bg-light">
    <div class="container">
        <div class="section-header">
            <h2>Why Book With Us?</h2>
        </div>

        <div class="benefits-grid">
            <div class="benefit-item">
                <i class="fas fa-certificate"></i>
                <h4>Certified Professionals</h4>
                <p>All our beauticians are certified and experienced</p>
            </div>

            <div class="benefit-item">
                <i class="fas fa-spa"></i>
                <h4>Premium Products</h4>
                <p>We use only branded and quality products</p>
            </div>

            <div class="benefit-item">
                <i class="fas fa-clock"></i>
                <h4>Flexible Timing</h4>
                <p>Convenient appointment slots to fit your schedule</p>
            </div>

            <div class="benefit-item">
                <i class="fas fa-hand-holding-usd"></i>
                <h4>Affordable Rates</h4>
                <p>Quality services at competitive prices</p>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Ready to Book Your Service?</h2>
            <p>Contact us today to schedule your appointment</p>
            <div class="cta-buttons">
                <a href="contact.php" class="btn btn-primary btn-lg">Book Appointment</a>
                <a href="https://wa.me/<?php echo WHATSAPP_NUMBER; ?>" class="btn btn-whatsapp btn-lg">
                    <i class="fab fa-whatsapp"></i> WhatsApp Us
                </a>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
