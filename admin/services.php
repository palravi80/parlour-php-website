<?php
$page_title = 'Manage Services';
require_once 'includes/header.php';

$db = getDB();
$success = '';
$error = '';
$action = $_GET['action'] ?? 'list';
$edit_id = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $post_action = $_POST['action'];

        if ($post_action === 'add' || $post_action === 'edit') {
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $price = floatval($_POST['price'] ?? 0);
            $duration = trim($_POST['duration'] ?? '');
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            if (empty($title) || empty($description) || $price <= 0) {
                $error = 'Please fill in all required fields.';
            } else {
                $image = '';
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                    $filename = $_FILES['image']['name'];
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                    if (in_array($ext, $allowed)) {
                        $image = uniqid() . '.' . $ext;
                        move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_DIR . $image);
                    }
                }

                try {
                    if ($post_action === 'add') {
                        $sql = "INSERT INTO services (title, description, price, duration, image, is_active) VALUES (:title, :description, :price, :duration, :image, :is_active)";
                        $stmt = $db->prepare($sql);
                    } else {
                        $id = intval($_POST['id']);
                        if ($image) {
                            $sql = "UPDATE services SET title = :title, description = :description, price = :price, duration = :duration, image = :image, is_active = :is_active WHERE id = :id";
                        } else {
                            $sql = "UPDATE services SET title = :title, description = :description, price = :price, duration = :duration, is_active = :is_active WHERE id = :id";
                        }
                        $stmt = $db->prepare($sql);
                        $stmt->bindParam(':id', $id);
                    }

                    $stmt->bindParam(':title', $title);
                    $stmt->bindParam(':description', $description);
                    $stmt->bindParam(':price', $price);
                    $stmt->bindParam(':duration', $duration);
                    if ($image) {
                        $stmt->bindParam(':image', $image);
                    }
                    $stmt->bindParam(':is_active', $is_active);

                    if ($stmt->execute()) {
                        $success = $post_action === 'add' ? 'Service added successfully!' : 'Service updated successfully!';
                        $action = 'list';
                    }
                } catch (PDOException $e) {
                    $error = 'Database error: ' . $e->getMessage();
                }
            }
        } elseif ($post_action === 'delete') {
            $id = intval($_POST['id']);
            try {
                $stmt = $db->prepare("DELETE FROM services WHERE id = :id");
                $stmt->bindParam(':id', $id);
                if ($stmt->execute()) {
                    $success = 'Service deleted successfully!';
                }
            } catch (PDOException $e) {
                $error = 'Error deleting service.';
            }
        }
    }
}

$services = $db->query("SELECT * FROM services ORDER BY created_at DESC")->fetchAll();

$edit_service = null;
if ($action === 'edit' && $edit_id) {
    $stmt = $db->prepare("SELECT * FROM services WHERE id = :id");
    $stmt->bindParam(':id', $edit_id);
    $stmt->execute();
    $edit_service = $stmt->fetch();
}
?>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-error"><?php echo $error; ?></div>
<?php endif; ?>

<?php if ($action === 'list'): ?>
    <div class="page-actions">
        <a href="?action=add" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Service
        </a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Price</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $service): ?>
                        <tr>
                            <td>
                                <?php if ($service['image']): ?>
                                    <img src="<?php echo UPLOAD_URL . $service['image']; ?>" alt="" class="table-image">
                                <?php else: ?>
                                    <span class="text-muted">No image</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($service['title']); ?></td>
                            <td>₹<?php echo number_format($service['price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($service['duration']); ?></td>
                            <td>
                                <?php if ($service['is_active']): ?>
                                    <span class="badge badge-success">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="table-actions">
                                <a href="?action=edit&id=<?php echo $service['id']; ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this service?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $service['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php elseif ($action === 'add' || $action === 'edit'): ?>
    <div class="page-actions">
        <a href="?action=list" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h3><?php echo $action === 'add' ? 'Add New Service' : 'Edit Service'; ?></h3>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="<?php echo $action; ?>">
                <?php if ($edit_service): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_service['id']; ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label for="title">Service Title *</label>
                    <input type="text" id="title" name="title" required value="<?php echo $edit_service ? htmlspecialchars($edit_service['title']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea id="description" name="description" rows="4" required><?php echo $edit_service ? htmlspecialchars($edit_service['description']) : ''; ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="price">Price (₹) *</label>
                        <input type="number" id="price" name="price" step="0.01" required value="<?php echo $edit_service ? $edit_service['price'] : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="duration">Duration</label>
                        <input type="text" id="duration" name="duration" placeholder="e.g., 1 hour" value="<?php echo $edit_service ? htmlspecialchars($edit_service['duration']) : ''; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="image">Service Image</label>
                    <input type="file" id="image" name="image" accept="image/*">
                    <?php if ($edit_service && $edit_service['image']): ?>
                        <div class="current-image">
                            <img src="<?php echo UPLOAD_URL . $edit_service['image']; ?>" alt="Current image">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" <?php echo (!$edit_service || $edit_service['is_active']) ? 'checked' : ''; ?>>
                        Active
                    </label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <?php echo $action === 'add' ? 'Add Service' : 'Update Service'; ?>
                    </button>
                    <a href="?action=list" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
