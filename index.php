<?php
$page_title = 'Home';
$page_description = 'Welcome to Khushi Ladies Beauty Parlour - Your trusted beauty destination';
require_once 'includes/header.php';
require_once 'config/db.php';

$db = getDB();

$stmt = $db->query("SELECT * FROM services WHERE is_active = 1 ORDER BY created_at DESC LIMIT 6");
$featured_services = $stmt->fetchAll();

$stmt = $db->query("SELECT * FROM gallery WHERE is_active = 1 ORDER BY created_at DESC LIMIT 6");
$gallery_items = $stmt->fetchAll();
?>

<section class="hero">
    <div class="hero-content">
        <h1>Welcome to <?php echo SITE_NAME; ?></h1>
        <p>Experience Beauty, Embrace Confidence</p>
        <div class="hero-buttons">
            <a href="services.php" class="btn btn-primary">Our Services</a>
            <a href="contact.php" class="btn btn-secondary">Book Appointment</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>Why Choose Us</h2>
            <p>We provide the best beauty services with premium quality products</p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-award"></i>
                </div>
                <h3>Expert Professionals</h3>
                <p>Highly trained and experienced beauticians</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-star"></i>
                </div>
                <h3>Premium Products</h3>
                <p>Only the finest beauty products and brands</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h3>Personalized Care</h3>
                <p>Customized services for your unique needs</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Hygienic Environment</h3>
                <p>Clean and sanitized equipment and space</p>
            </div>
        </div>
    </div>
</section>

<section class="section bg-light">
    <div class="container">
        <div class="section-header">
            <h2>Our Services</h2>
            <p>Explore our wide range of beauty services</p>
        </div>

        <div class="services-grid">
            <?php foreach ($featured_services as $service): ?>
                <div class="service-card">
                    <?php if ($service['image']): ?>
                        <div class="service-image">
                            <img src="<?php echo UPLOAD_URL . $service['image']; ?>" alt="<?php echo htmlspecialchars($service['title']); ?>">
                        </div>
                    <?php endif; ?>
                    <div class="service-content">
                        <h3><?php echo htmlspecialchars($service['title']); ?></h3>
                        <p><?php echo htmlspecialchars(substr($service['description'], 0, 100)); ?>...</p>
                        <div class="service-meta">
                            <span class="price">₹<?php echo number_format($service['price'], 2); ?></span>
                            <?php if ($service['duration']): ?>
                                <span class="duration"><i class="far fa-clock"></i> <?php echo htmlspecialchars($service['duration']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center" style="margin-top: 2rem;">
            <a href="services.php" class="btn btn-primary">View All Services</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>Our Gallery</h2>
            <p>Take a look at our beautiful transformations</p>
        </div>

        <div class="gallery-grid">
            <?php foreach ($gallery_items as $item): ?>
                <div class="gallery-item">
                    <img src="<?php echo UPLOAD_URL . $item['image']; ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                    <div class="gallery-overlay">
                        <h4><?php echo htmlspecialchars($item['title']); ?></h4>
                        <?php if ($item['category']): ?>
                            <span class="category"><?php echo htmlspecialchars($item['category']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center" style="margin-top: 2rem;">
            <a href="gallery.php" class="btn btn-primary">View Full Gallery</a>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Ready to Transform Your Look?</h2>
            <p>Book your appointment today and experience the best beauty services</p>
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
