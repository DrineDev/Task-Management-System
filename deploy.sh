#!/bin/bash

# Update system
sudo apt update
sudo apt upgrade -y

# Install PHP and required extensions
sudo apt install -y php8.1-fpm php8.1-mysql php8.1-xml php8.1-curl php8.1-mbstring php8.1-zip php8.1-gd php8.1-bcmath

# Install Composer
if ! command -v composer &> /dev/null; then
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
fi

# Install Node.js and NPM
if ! command -v node &> /dev/null; then
    curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
    sudo apt install -y nodejs
fi

# Install Nginx
sudo apt install -y nginx

# Create project directory
sudo mkdir -p /var/www/task-management
sudo chown -R $USER:$USER /var/www/task-management

# Copy project files
cp -r ./* /var/www/task-management/

# Set up Nginx configuration
sudo tee /etc/nginx/sites-available/task-management << EOF
server {
    listen 80;
    server_name _;
    root /var/www/task-management/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

# Enable the site
sudo ln -s /etc/nginx/sites-available/task-management /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default

# Set proper permissions
cd /var/www/task-management
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Install dependencies
composer install --no-dev --optimize-autoloader
npm install
npm run build

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Restart services
sudo systemctl restart php8.1-fpm
sudo systemctl restart nginx

echo "Deployment completed! Please configure your .env file with proper database credentials and other settings." 