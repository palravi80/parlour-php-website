<?php
$page_title = 'Gallery';
$page_description = 'View our portfolio of beautiful transformations at Khushi Ladies Beauty Parlour';
require_once 'includes/header.php';
require_once 'config/db.php';

$db = getDB();

$category_filter = isset($_GET['category']) ? $_GET['category'] : '';

$sql = "SELECT * FROM gallery WHERE is_active = 1";
if ($category_filter) {
    $sql .= " AND category = :category";
}
$sql .= " ORDER BY created_at DESC";

$stmt = $db->prepare($sql);
if ($category_filter) {
    $stmt->bindParam(':category', $category_filter);
}
$stmt->execute();
$gallery_items = $stmt->fetchAll();

$categories_stmt = $db->query("SELECT DISTINCT category FROM gallery WHERE is_active = 1 AND category IS NOT NULL ORDER BY category");
$categories = $categories_stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<section class="page-header">
    <div class="container">
        <h1>Our Gallery</h1>
        <p>Witness the magic of transformation</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (count($categories) > 0): ?>
            <div class="gallery-filter">
                <button class="filter-btn <?php echo $category_filter === '' ? 'active' : ''; ?>" onclick="window.location.href='gallery.php'">All</button>
                <?php foreach ($categories as $category): ?>
                    <button class="filter-btn <?php echo $category_filter === $category ? 'active' : ''; ?>" onclick="window.location.href='gallery.php?category=<?php echo urlencode($category); ?>'">
                        <?php echo htmlspecialchars($category); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="gallery-grid">
            <?php if (count($gallery_items) > 0): ?>
                <?php foreach ($gallery_items as $item): ?>
                    <div class="gallery-item">
                        <img src="<?php echo UPLOAD_URL . $item['image']; ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" onclick="openLightbox('<?php echo UPLOAD_URL . $item['image']; ?>', '<?php echo htmlspecialchars($item['title']); ?>')">
                        <div class="gallery-overlay">
                            <h4><?php echo htmlspecialchars($item['title']); ?></h4>
                            <?php if ($item['category']): ?>
                                <span class="category"><?php echo htmlspecialchars($item['category']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-results">
                    <p>No gallery items available.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<div id="lightbox" class="lightbox" onclick="closeLightbox()">
    <span class="lightbox-close">&times;</span>
    <img class="lightbox-content" id="lightboxImage">
    <div class="lightbox-caption" id="lightboxCaption"></div>
</div>

<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Want to Be Part of Our Gallery?</h2>
            <p>Book your appointment and let us create magic</p>
            <a href="contact.php" class="btn btn-primary btn-lg">Book Now</a>
        </div>
    </div>
</section>

<script>
function openLightbox(imageSrc, caption) {
    document.getElementById('lightbox').style.display = 'flex';
    document.getElementById('lightboxImage').src = imageSrc;
    document.getElementById('lightboxCaption').textContent = caption;
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    document.getElementById('lightbox').style.display = 'none';
    document.body.style.overflow = 'auto';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeLightbox();
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>
