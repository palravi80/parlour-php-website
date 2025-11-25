# Quick Deployment Guide for AWS EC2

## Prerequisites
- AWS EC2 instance (Ubuntu 20.04+)
- SSH access to the server
- Domain name (optional)

## Quick Setup Commands

### 1. Install LAMP Stack
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Apache, MySQL, PHP
sudo apt install -y apache2 mysql-server php libapache2-mod-php php-mysql php-gd php-mbstring php-xml

# Enable Apache modules
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### 2. Secure MySQL
```bash
sudo mysql_secure_installation
# Follow prompts: Set root password, remove anonymous users, disallow root login remotely
```

### 3. Create Database
```bash
sudo mysql -u root -p
```

```sql
CREATE DATABASE khushee_parlour CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'khushee_user'@'localhost' IDENTIFIED BY 'your_secure_password_here';
GRANT ALL PRIVILEGES ON khushee_parlour.* TO 'khushee_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 4. Configure Apache
```bash
sudo nano /etc/apache2/sites-available/000-default.conf
```

Add inside `<VirtualHost *:80>`:
```apache
<Directory /var/www/html>
    Options -Indexes +FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```

Save and restart:
```bash
sudo systemctl restart apache2
```

### 5. Upload Project Files
```bash
# Option A: Using SCP from your local machine
scp -i your-key.pem -r * ubuntu@your-ec2-ip:/tmp/project/

# Then on EC2:
sudo rm /var/www/html/index.html
sudo mv /tmp/project/* /var/www/html/

# Option B: Using Git
cd /var/www/html
sudo git clone your-repository-url .
```

### 6. Import Database
```bash
mysql -u khushee_user -p khushee_parlour < /var/www/html/database.sql
```

### 7. Configure .env File
```bash
cd /var/www/html
sudo cp .env.example .env
sudo nano .env
```

Update these values:
```
DB_HOST=localhost
DB_NAME=khushee_parlour
DB_USER=khushee_user
DB_PASS=your_secure_password_here

SITE_URL=http://your-ec2-public-ip
# or http://yourdomain.com

WHATSAPP_NUMBER=919876543210
GOOGLE_MAP_EMBED=your_google_maps_embed_url
```

### 8. Set Permissions
```bash
sudo mkdir -p /var/www/html/uploads
sudo chown -R www-data:www-data /var/www/html
sudo chmod -R 755 /var/www/html
sudo chmod -R 775 /var/www/html/uploads
sudo chmod 600 /var/www/html/.env
```

### 9. Configure EC2 Security Group
In AWS Console → EC2 → Security Groups:
- Add Inbound Rule: HTTP (80) from 0.0.0.0/0
- Add Inbound Rule: HTTPS (443) from 0.0.0.0/0

### 10. Test the Site
Visit: `http://your-ec2-public-ip`

Admin Login:
- URL: `http://your-ec2-public-ip/admin/`
- Username: `admin`
- Password: `password`

**IMPORTANT**: Change the admin password immediately!

## Change Admin Password

```bash
# Generate new password hash
php -r "echo password_hash('YourNewPassword123', PASSWORD_DEFAULT);"

# Copy the hash, then:
mysql -u khushee_user -p khushee_parlour
```

```sql
UPDATE admin_users
SET password = 'paste_the_hash_here'
WHERE username = 'admin';
EXIT;
```

## Optional: Setup SSL Certificate (Recommended)

```bash
# Install Certbot
sudo apt install -y certbot python3-certbot-apache

# Get certificate (requires domain name)
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com

# Test auto-renewal
sudo certbot renew --dry-run
```

## Troubleshooting

### Can't access the site
```bash
# Check Apache status
sudo systemctl status apache2

# Check Apache error logs
sudo tail -f /var/log/apache2/error.log

# Verify port 80 is open
sudo netstat -tulpn | grep :80
```

### Database connection error
```bash
# Check MySQL status
sudo systemctl status mysql

# Verify database exists
mysql -u khushee_user -p -e "SHOW DATABASES;"

# Check .env file
cat /var/www/html/.env
```

### Images not uploading
```bash
# Check uploads directory
ls -la /var/www/html/uploads

# Fix permissions
sudo chmod 775 /var/www/html/uploads
sudo chown www-data:www-data /var/www/html/uploads

# Check PHP settings
php -i | grep upload_max_filesize
```

### 404 errors on pages
```bash
# Verify .htaccess exists
cat /var/www/html/.htaccess

# Check mod_rewrite is enabled
sudo apache2ctl -M | grep rewrite

# If not enabled:
sudo a2enmod rewrite
sudo systemctl restart apache2
```

## Maintenance

### Backup Database
```bash
mysqldump -u khushee_user -p khushee_parlour > backup_$(date +%Y%m%d_%H%M%S).sql
```

### Backup Files
```bash
sudo tar -czf website_backup_$(date +%Y%m%d_%H%M%S).tar.gz /var/www/html
```

### Update System
```bash
sudo apt update
sudo apt upgrade -y
sudo systemctl restart apache2
```

## Post-Deployment Checklist

- [ ] Website loads correctly
- [ ] All pages are accessible
- [ ] Admin panel login works
- [ ] Changed default admin password
- [ ] Images can be uploaded
- [ ] Contact form works
- [ ] WhatsApp link works
- [ ] Google Maps displays
- [ ] Database is backed up
- [ ] SSL certificate installed (if using domain)
- [ ] Security group configured
- [ ] .env file is secured (chmod 600)

## Common URLs

- **Homepage**: http://your-ip/
- **Admin Login**: http://your-ip/admin/login.php
- **Admin Dashboard**: http://your-ip/admin/
- **Services**: http://your-ip/services.php
- **Gallery**: http://your-ip/gallery.php
- **Contact**: http://your-ip/contact.php

## Support

For deployment issues, check:
1. Apache error logs: `/var/log/apache2/error.log`
2. PHP error logs: `/var/log/apache2/error.log`
3. File permissions: `ls -la /var/www/html`
4. Database connection: Test with `mysql -u khushee_user -p`
