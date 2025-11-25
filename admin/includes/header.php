<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../../config/db.php';
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Admin - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-body">
    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h2><?php echo SITE_NAME; ?></h2>
                <p>Admin Panel</p>
            </div>

            <nav class="sidebar-nav">
                <a href="index.php" class="nav-item <?php echo $current_page === 'index' ? 'active' : ''; ?>">
                    <i class="fas fa-dashboard"></i> Dashboard
                </a>
                <a href="services.php" class="nav-item <?php echo $current_page === 'services' ? 'active' : ''; ?>">
                    <i class="fas fa-spa"></i> Services
                </a>
                <a href="gallery.php" class="nav-item <?php echo $current_page === 'gallery' ? 'active' : ''; ?>">
                    <i class="fas fa-images"></i> Gallery
                </a>
                <a href="contacts.php" class="nav-item <?php echo $current_page === 'contacts' ? 'active' : ''; ?>">
                    <i class="fas fa-envelope"></i> Contact Messages
                </a>
                <a href="../index.php" class="nav-item" target="_blank">
                    <i class="fas fa-globe"></i> View Website
                </a>
                <a href="logout.php" class="nav-item">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </nav>
        </aside>

        <main class="admin-main">
            <header class="admin-header">
                <div class="header-content">
                    <h1><?php echo isset($page_title) ? $page_title : 'Dashboard'; ?></h1>
                    <div class="header-user">
                        <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                    </div>
                </div>
            </header>

            <div class="admin-content">
