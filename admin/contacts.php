<?php
$page_title = 'Contact Messages';
require_once 'includes/header.php';

$db = getDB();
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $id = intval($_POST['id'] ?? 0);

    if ($action === 'mark_read' && $id) {
        try {
            $stmt = $db->prepare("UPDATE contacts SET is_read = 1 WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $success = 'Message marked as read.';
        } catch (PDOException $e) {
            $error = 'Error updating message.';
        }
    } elseif ($action === 'delete' && $id) {
        try {
            $stmt = $db->prepare("DELETE FROM contacts WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $success = 'Message deleted successfully.';
        } catch (PDOException $e) {
            $error = 'Error deleting message.';
        }
    }
}

$contacts = $db->query("SELECT * FROM contacts ORDER BY created_at DESC")->fetchAll();
?>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-error"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card">
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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($contacts) > 0): ?>
                    <?php foreach ($contacts as $contact): ?>
                        <tr class="<?php echo $contact['is_read'] ? '' : 'unread-row'; ?>">
                            <td><?php echo htmlspecialchars($contact['name']); ?></td>
                            <td><?php echo htmlspecialchars($contact['email']); ?></td>
                            <td><?php echo htmlspecialchars($contact['phone']); ?></td>
                            <td>
                                <div class="message-preview">
                                    <?php echo nl2br(htmlspecialchars($contact['message'])); ?>
                                </div>
                            </td>
                            <td><?php echo date('M d, Y H:i', strtotime($contact['created_at'])); ?></td>
                            <td>
                                <?php if ($contact['is_read']): ?>
                                    <span class="badge badge-success">Read</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Unread</span>
                                <?php endif; ?>
                            </td>
                            <td class="table-actions">
                                <?php if (!$contact['is_read']): ?>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="action" value="mark_read">
                                        <input type="hidden" name="id" value="<?php echo $contact['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-success" title="Mark as read">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $contact['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No messages yet</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
