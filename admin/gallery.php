<?php
$page_title = 'Manage Gallery';
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
            $category = trim($_POST['category'] ?? '');
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            if (empty($title)) {
                $error = 'Please enter a title.';
            } else {
                $image = '';
                $image_required = ($post_action === 'add');

                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                    $filename = $_FILES['image']['name'];
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                    if (in_array($ext, $allowed)) {
                        $image = uniqid() . '.' . $ext;
                        move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_DIR . $image);
                    } else {
                        $error = 'Invalid image format. Allowed: JPG, PNG, GIF';
                    }
                } elseif ($image_required) {
                    $error = 'Please upload an image.';
                }

                if (!$error) {
                    try {
                        if ($post_action === 'add') {
                            $sql = "INSERT INTO gallery (title, image, category, is_active) VALUES (:title, :image, :category, :is_active)";
                            $stmt = $db->prepare($sql);
                            $stmt->bindParam(':image', $image);
                        } else {
                            $id = intval($_POST['id']);
                            if ($image) {
                                $sql = "UPDATE gallery SET title = :title, image = :image, category = :category, is_active = :is_active WHERE id = :id";
                            } else {
                                $sql = "UPDATE gallery SET title = :title, category = :category, is_active = :is_active WHERE id = :id";
                            }
                            $stmt = $db->prepare($sql);
                            $stmt->bindParam(':id', $id);
                            if ($image) {
                                $stmt->bindParam(':image', $image);
                            }
                        }

                        $stmt->bindParam(':title', $title);
                        $stmt->bindParam(':category', $category);
                        $stmt->bindParam(':is_active', $is_active);

                        if ($stmt->execute()) {
                            $success = $post_action === 'add' ? 'Gallery item added successfully!' : 'Gallery item updated successfully!';
                            $action = 'list';
                        }
                    } catch (PDOException $e) {
                        $error = 'Database error: ' . $e->getMessage();
                    }
                }
            }
        } elseif ($post_action === 'delete') {
            $id = intval($_POST['id']);
            try {
                $stmt = $db->prepare("DELETE FROM gallery WHERE id = :id");
                $stmt->bindParam(':id', $id);
                if ($stmt->execute()) {
                    $success = 'Gallery item deleted successfully!';
                }
            } catch (PDOException $e) {
                $error = 'Error deleting gallery item.';
            }
        }
    }
}

$gallery_items = $db->query("SELECT * FROM gallery ORDER BY created_at DESC")->fetchAll();

$edit_item = null;
if ($action === 'edit' && $edit_id) {
    $stmt = $db->prepare("SELECT * FROM gallery WHERE id = :id");
    $stmt->bindParam(':id', $edit_id);
    $stmt->execute();
    $edit_item = $stmt->fetch();
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
            <i class="fas fa-plus"></i> Add New Image
        </a>
    </div>

    <div class="gallery-admin-grid">
        <?php foreach ($gallery_items as $item): ?>
            <div class="gallery-admin-item">
                <div class="gallery-admin-image">
                    <img src="<?php echo UPLOAD_URL . $item['image']; ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                </div>
                <div class="gallery-admin-content">
                    <h4><?php echo htmlspecialchars($item['title']); ?></h4>
                    <?php if ($item['category']): ?>
                        <span class="badge badge-info"><?php echo htmlspecialchars($item['category']); ?></span>
                    <?php endif; ?>
                    <?php if ($item['is_active']): ?>
                        <span class="badge badge-success">Active</span>
                    <?php else: ?>
                        <span class="badge badge-secondary">Inactive</span>
                    <?php endif; ?>
                </div>
                <div class="gallery-admin-actions">
                    <a href="?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this item?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

<?php elseif ($action === 'add' || $action === 'edit'): ?>
    <div class="page-actions">
        <a href="?action=list" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h3><?php echo $action === 'add' ? 'Add New Gallery Image' : 'Edit Gallery Image'; ?></h3>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="<?php echo $action; ?>">
                <?php if ($edit_item): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_item['id']; ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label for="title">Title *</label>
                    <input type="text" id="title" name="title" required value="<?php echo $edit_item ? htmlspecialchars($edit_item['title']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="category">Category</label>
                    <input type="text" id="category" name="category" placeholder="e.g., Bridal, Makeup, Hair" value="<?php echo $edit_item ? htmlspecialchars($edit_item['category']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="image">Image <?php echo $action === 'add' ? '*' : ''; ?></label>
                    <input type="file" id="image" name="image" accept="image/*" <?php echo $action === 'add' ? 'required' : ''; ?>>
                    <?php if ($edit_item && $edit_item['image']): ?>
                        <div class="current-image">
                            <img src="<?php echo UPLOAD_URL . $edit_item['image']; ?>" alt="Current image">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" <?php echo (!$edit_item || $edit_item['is_active']) ? 'checked' : ''; ?>>
                        Active
                    </label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <?php echo $action === 'add' ? 'Add Image' : 'Update Image'; ?>
                    </button>
                    <a href="?action=list" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
