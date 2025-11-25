CREATE DATABASE IF NOT EXISTS khushee_parlour CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE khushee_parlour;

CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    duration VARCHAR(50),
    image VARCHAR(255),
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    image VARCHAR(255) NOT NULL,
    category VARCHAR(100),
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_active (is_active),
    INDEX idx_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_read (is_read),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO admin_users (username, password, email) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ravi456pal@gmail.com');

INSERT INTO services (title, description, price, duration, image) VALUES
('Bridal Makeup', 'Complete bridal makeup with premium products and HD finish', 15000.00, '3 hours', 'bridal-makeup.jpg'),
('Hair Styling', 'Professional hair styling for all occasions', 2000.00, '1 hour', 'hair-styling.jpg'),
('Facial Treatment', 'Rejuvenating facial with natural ingredients', 1500.00, '45 mins', 'facial.jpg'),
('Manicure & Pedicure', 'Complete hand and foot care with nail art', 1200.00, '1.5 hours', 'mani-pedi.jpg'),
('Hair Spa', 'Deep conditioning hair spa treatment', 1800.00, '1 hour', 'hair-spa.jpg'),
('Threading', 'Eyebrow and facial threading', 200.00, '15 mins', 'threading.jpg');

INSERT INTO gallery (title, image, category) VALUES
('Bridal Look 1', 'gallery-1.jpg', 'Bridal'),
('Hair Style 1', 'gallery-2.jpg', 'Hair'),
('Party Makeup 1', 'gallery-3.jpg', 'Makeup'),
('Bridal Look 2', 'gallery-4.jpg', 'Bridal'),
('Hair Style 2', 'gallery-5.jpg', 'Hair'),
('Party Makeup 2', 'gallery-6.jpg', 'Makeup');
