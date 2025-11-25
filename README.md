# Khushee Ladies Beauty Parlour Website

A complete LAMP stack website for Khushee Ladies Beauty Parlour with admin dashboard.

## Features

### Public Website
- **Home Page**: Hero section, featured services, gallery preview, call-to-action
- **About Page**: Company information, values, mission, statistics
- **Services Page**: Complete list of beauty services with prices and durations
- **Gallery Page**: Image gallery with category filtering and lightbox view
- **Contact Page**: Contact form, business info, WhatsApp integration, Google Maps

### Admin Dashboard
- **Dashboard**: Statistics overview, recent messages, quick actions
- **Services Management**: Add, edit, delete services with image upload
- **Gallery Management**: Add, edit, delete gallery images with categories
- **Contact Messages**: View and manage customer inquiries
- **Secure Authentication**: Login system with password protection

## Technology Stack

- **Frontend**: HTML5, CSS3, JavaScript
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Server**: Apache with mod_rewrite

## Installation Instructions

### Prerequisites
- AWS EC2 instance (Ubuntu 20.04 or later recommended)
- Apache 2.4+
- PHP 7.4+ with PDO extension
- MySQL 5.7+

### Step 1: Setup LAMP Stack on EC2

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Apache
sudo apt install apache2 -y
sudo systemctl start apache2
sudo systemctl enable apache2

# Install MySQL
sudo apt install mysql-server -y
sudo mysql_secure_installation

# Install PHP
sudo apt install php libapache2-mod-php php-mysql php-gd php-mbstring php-xml -y

# Enable Apache modules
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### Step 2: Configure Apache

```bash
# Edit Apache config to allow .htaccess
sudo nano /etc/apache2/sites-available/000-default.conf
```

Add this inside the `<VirtualHost>` block:

```apache
<Directory /var/www/html>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```

```bash
# Restart Apache
sudo systemctl restart apache2
```

### Step 3: Upload Files

```bash
# Navigate to web root
cd /var/www/html

# Remove default index
sudo rm index.html

# Upload all project files here (use SCP, SFTP, or Git)
# Or clone from your repository
```

### Step 4: Create Database

```bash
# Login to MySQL
sudo mysql -u root -p

# Create database and user
CREATE DATABASE khushee_parlour;
CREATE USER 'khushee_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON khushee_parlour.* TO 'khushee_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Import database schema
mysql -u khushee_user -p khushee_parlour < /var/www/html/database.sql
```

### Step 5: Configure Environment

```bash
# Copy .env.example to .env
sudo cp .env.example .env

# Edit .env file
sudo nano .env
```

Update these values:
```
DB_HOST=localhost
DB_NAME=khushee_parlour
DB_USER=khushee_user
DB_PASS=your_secure_password
DB_CHARSET=utf8mb4

SITE_NAME=Khushee Ladies Beauty Parlour
SITE_URL=http://your-ec2-ip-or-domain
ADMIN_EMAIL=admin@khusheeparlour.com

WHATSAPP_NUMBER=919876543210
GOOGLE_MAP_EMBED=https://www.google.com/maps/embed?pb=your_map_url
```

### Step 6: Create Upload Directory

```bash
# Create uploads directory
sudo mkdir -p /var/www/html/uploads

# Set permissions
sudo chown -R www-data:www-data /var/www/html
sudo chmod -R 755 /var/www/html
sudo chmod -R 775 /var/www/html/uploads
```

### Step 7: Configure Security

```bash
# Secure .env file
sudo chmod 600 .env

# Set proper file permissions
sudo find /var/www/html -type f -exec chmod 644 {} \;
sudo find /var/www/html -type d -exec chmod 755 {} \;
```

### Step 8: Configure EC2 Security Group

In AWS Console:
1. Go to EC2 → Security Groups
2. Add inbound rules:
   - HTTP (Port 80) - Source: 0.0.0.0/0
   - HTTPS (Port 443) - Source: 0.0.0.0/0
   - SSH (Port 22) - Source: Your IP

## Default Admin Credentials

**Username**: admin
**Password**: password

**IMPORTANT**: Change the admin password immediately after first login!

To change password, run this in MySQL:

```sql
UPDATE admin_users
SET password = '$2y$10$YOUR_NEW_HASHED_PASSWORD'
WHERE username = 'admin';
```

Generate hash with PHP:
```php
php -r "echo password_hash('your_new_password', PASSWORD_DEFAULT);"
```

## Usage

### Access the Website
- **Public Website**: `http://your-ec2-ip/`
- **Admin Dashboard**: `http://your-ec2-ip/admin/`

### Admin Dashboard Features

1. **Services Management**
   - Add new services with images
   - Edit existing services
   - Delete services
   - Set service prices and duration
   - Toggle active/inactive status

2. **Gallery Management**
   - Upload images with titles
   - Categorize images (Bridal, Makeup, Hair, etc.)
   - Delete unwanted images
   - Toggle visibility

3. **Contact Messages**
   - View all customer inquiries
   - Mark messages as read
   - Delete old messages

## Customization

### Update WhatsApp Number
Edit `.env` file and update `WHATSAPP_NUMBER` with your number (format: 919876543210)

### Update Google Maps
1. Go to [Google Maps](https://www.google.com/maps)
2. Search for your location
3. Click "Share" → "Embed a map"
4. Copy the iframe src URL
5. Update `GOOGLE_MAP_EMBED` in `.env`

### Change Theme Colors
Edit `/assets/css/style.css` and update CSS variables:
```css
:root {
    --primary: #ff9eb3;      /* Main pink color */
    --primary-dark: #ff7a9a; /* Darker pink */
    --secondary: #ffd4e0;    /* Light pink */
    --accent: #ffb6c9;       /* Accent pink */
}
```

## Troubleshooting

### 500 Internal Server Error
- Check Apache error logs: `sudo tail -f /var/log/apache2/error.log`
- Verify .htaccess file exists and Apache mod_rewrite is enabled
- Check file permissions

### Database Connection Error
- Verify MySQL is running: `sudo systemctl status mysql`
- Check .env credentials
- Ensure database exists: `mysql -u root -p -e "SHOW DATABASES;"`

### Images Not Uploading
- Check uploads directory exists: `ls -la /var/www/html/uploads`
- Verify permissions: `sudo chmod 775 /var/www/html/uploads`
- Check PHP upload settings in php.ini

### Can't Access Admin Panel
- Clear browser cache
- Check if session directory is writable
- Verify admin user exists in database

## Backup

### Database Backup
```bash
mysqldump -u khushee_user -p khushee_parlour > backup_$(date +%Y%m%d).sql
```

### Files Backup
```bash
sudo tar -czf backup_$(date +%Y%m%d).tar.gz /var/www/html
```

## Security Best Practices

1. **Change default admin password immediately**
2. **Use strong database passwords**
3. **Keep PHP and MySQL updated**
4. **Enable HTTPS with SSL certificate (Let's Encrypt)**
5. **Regular backups**
6. **Monitor Apache logs for suspicious activity**

## SSL Certificate (Optional but Recommended)

```bash
# Install Certbot
sudo apt install certbot python3-certbot-apache -y

# Get certificate
sudo certbot --apache -d yourdomain.com

# Auto-renewal
sudo certbot renew --dry-run
```

## Support

For issues or questions, contact the development team.

## License

Proprietary - © 2025 Khushee Ladies Beauty Parlour
