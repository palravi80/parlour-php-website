<?php
require_once __DIR__ . '/../config/config.php';
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?><?php echo SITE_NAME; ?></title>
    <meta name="description" content="<?php echo isset($page_description) ? $page_description : 'Premium beauty parlour services for women'; ?>">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <div class="container">
                <div class="nav-wrapper">
                    <a href="index.php" class="logo">
                        <i class="fas fa-spa"></i>
                        <span><?php echo SITE_NAME; ?></span>
                    </a>

                    <button class="mobile-toggle" id="mobileToggle">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>

                    <ul class="nav-menu" id="navMenu">
                        <li><a href="index.php" class="<?php echo $current_page === 'index' ? 'active' : ''; ?>">Home</a></li>
                        <li><a href="about.php" class="<?php echo $current_page === 'about' ? 'active' : ''; ?>">About</a></li>
                        <li><a href="services.php" class="<?php echo $current_page === 'services' ? 'active' : ''; ?>">Services</a></li>
                        <li><a href="gallery.php" class="<?php echo $current_page === 'gallery' ? 'active' : ''; ?>">Gallery</a></li>
                        <li><a href="contact.php" class="<?php echo $current_page === 'contact' ? 'active' : ''; ?>">Contact</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="main-content">
