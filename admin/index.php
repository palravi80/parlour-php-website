<?php
$page_title = 'Dashboard';
require_once 'includes/header.php';

$db = getDB();

$stats = [
    'services' => $db->query("SELECT COUNT(*) FROM services WHERE is_active = 1")->fetchColumn(),
    'gallery' => $db->query("SELECT COUNT(*) FROM gallery WHERE is_active = 1")->fetchColumn(),
    'contacts' => $db->query("SELECT COUNT(*) FROM contacts")->fetchColumn(),
    'unread_contacts' => $db->query("SELECT COUNT(*) FROM contacts WHERE is_read = 0")->fetchColumn(),
];

$recent_contacts = $db->query("SELECT * FROM contacts ORDER BY created_at DESC LIMIT 5")->fetchAll();
?>

<div class="dashboard-stats">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-spa"></i>
        </div>
        <div class="stat-content">
            <h3><?php echo $stats['services']; ?></h3>
            <p>Active Services</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-images"></i>
        </div>
        <div class="stat-content">
            <h3><?php echo $stats['gallery']; ?></h3>
            <p>Gallery Images</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-envelope"></i>
        </div>
        <div class="stat-content">
            <h3><?php echo $stats['contacts']; ?></h3>
            <p>Total Messages</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-envelope-open"></i>
        </div>
        <div class="stat-content">
            <h3><?php echo $stats['unread_contacts']; ?></h3>
            <p>Unread Messages</p>
        </div>
    </div>
</div>

<div class="dashboard-section">
    <div class="section-header">
        <h2>Recent Contact Messages</h2>
        <a href="contacts.php" class="btn btn-primary">View All</a>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($recent_contacts) > 0): ?>
                    <?php foreach ($recent_contacts as $contact): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($contact['name']); ?></td>
                            <td><?php echo htmlspecialchars($contact['email']); ?></td>
                            <td><?php echo htmlspecialchars($contact['phone']); ?></td>
                            <td><?php echo htmlspecialchars(substr($contact['message'], 0, 50)) . '...'; ?></td>
                            <td><?php echo date('M d, Y', strtotime($contact['created_at'])); ?></td>
                            <td>
                                <?php if ($contact['is_read']): ?>
                                    <span class="badge badge-success">Read</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Unread</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No messages yet</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="dashboard-section">
    <div class="section-header">
        <h2>Quick Actions</h2>
    </div>

    <div class="quick-actions">
        <a href="services.php?action=add" class="action-card">
            <i class="fas fa-plus-circle"></i>
            <span>Add New Service</span>
        </a>

        <a href="gallery.php?action=add" class="action-card">
            <i class="fas fa-plus-circle"></i>
            <span>Add Gallery Image</span>
        </a>

        <a href="contacts.php" class="action-card">
            <i class="fas fa-envelope"></i>
            <span>View Messages</span>
        </a>

        <a href="../index.php" class="action-card" target="_blank">
            <i class="fas fa-globe"></i>
            <span>View Website</span>
        </a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
