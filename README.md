# Task Management System

A web application built with Laravel, styled with Tailwind CSS, and using Supabase as the backend database service.

## Prerequisites

Before setting up this project, you'll need to install the following software:

- XAMPP (includes PHP 8.1 or higher)
- Composer (PHP package manager)
- Node.js and npm
- Git

## Installation Guide

### 1. Installing PHP and Composer

Open CMD as Administrator and run these commands:

```cmd
powershell -Command "New-Item -ItemType Directory -Force -Path C:\php"
powershell -Command "Invoke-WebRequest -Uri https://windows.php.net/downloads/releases/php-8.2.10-Win32-vs16-x64.zip -OutFile C:\php\php.zip"
powershell -Command "Expand-Archive -Path C:\php\php.zip -DestinationPath C:\php -Force"
powershell -Command "Copy-Item C:\php\php.ini-development C:\php\php.ini"
powershell -Command "[Environment]::SetEnvironmentVariable('Path', [Environment]::GetEnvironmentVariable('Path', 'Machine') + ';C:\php', 'Machine')"
```

Verify PHP installation:
- Open a new CMD window
- Run:
```cmd
php -v
```

Install Composer:
```cmd
powershell -Command "Invoke-WebRequest -Uri https://getcomposer.org/Composer-Setup.exe -OutFile C:\php\composer-setup.exe"
powershell -Command "Start-Process -FilePath C:\php\composer-setup.exe -ArgumentList '/VERYSILENT' -Wait"
```

Verify Composer installation:
```cmd
composer --version
```

### 2. Installing Node.js

1. Download Node.js:
   - Visit: https://nodejs.org/
   - Download the LTS (Long Term Support) version
   - Run the downloaded .msi installer

2. Verify Node.js and npm installation:
   - Open a new CMD window
   - Run:
   ```cmd
   node --version
   npm --version
   ```

### 3. Configure PHP for Laravel

1. Open `C:\xampp\php\php.ini` in a text editor
2. Uncomment (remove the semicolon) from these lines:
   ```ini
   extension=zip
   extension=pdo_pgsql
   extension=pgsql
   ```

### 4. Setting Up the Project

1. Clone the repository to XAMPP's htdocs:
   ```cmd
   cd C:\xampp\htdocs
   git clone https://github.com/DrineDev/Task-Management-System.git
   cd Task-Management-System
   ```

2. Copy the provided `.env` file to the project root directory

3. Install Laravel dependencies:
   ```cmd
   composer install
   php artisan key:generate
   ```

4. Install Node.js dependencies and build assets:
   ```cmd
   npm install
   npm run dev
   ```

### 5. Running the Application

1. Make sure XAMPP's Apache service is running:
   - Open XAMPP Control Panel
   - Click "Start" next to Apache

2. In a separate CMD window, start Vite for Tailwind CSS processing:
   ```cmd
   cd C:\xampp\htdocs\Task-Management-System
   npm run dev
   ```

3. Visit `http://localhost/Task-Management-System/public` in your browser

## Troubleshooting

### Common Issues

1. **Apache not starting**:
   - Check if port 80 is in use by another application
   - Try changing Apache's port in XAMPP Control Panel
   - Make sure you have administrator privileges

2. **Composer not found**:
   - Make sure Composer is installed correctly
   - Check if PHP is in your system PATH
   - Try closing and reopening CMD

3. **Node.js/npm not found**:
   - Make sure Node.js is installed correctly
   - Try closing and reopening CMD
   - Verify the installation in Control Panel

4. **Permission issues**:
   - Run CMD as Administrator
   - Make sure you have write permissions in the project directory
   - Check XAMPP's Apache user permissions

5. **Vite not working**:
   - Make sure you're running `npm run dev` in the project directory
   - Check if Node.js and npm are properly installed
   - Try clearing npm cache: `npm cache clean --force`

## Additional Notes

- Make sure to keep your `.env` file secure and never commit it to version control
- The project uses Tailwind CSS for styling, which is automatically processed by Vite
- For any database-related issues, contact the project administrator for the correct `.env` configuration
- When deploying updates, remember to:
  1. Pull the latest changes
  2. Run `composer install`
  3. Run `npm install`
  4. Run `npm run build` (for production)
  5. Clear Laravel cache: `php artisan cache:clear`

## Verification Steps

After installation, verify everything is working correctly:

1. Visit `http://localhost:8000` in your browser. You should see the application homepage.

2. Check PHP info page (add this route to your web.php first):
   ```php
   Route::get('/phpinfo', function() {
       phpinfo();
   });
   ```

3. Test database connection:
   ```cmd
   php artisan tinker
   # Then in Tinker:
   DB::connection()->getPdo();
   # If you don't see any errors, the database connection is successful
   ```

4. Test Supabase connection:
   Create a test route in `routes/web.php`:
   ```php
   Route::get('/test-supabase', function() {
       $supabase = new \Supabase\Client(
           env('SUPABASE_URL'),
           env('SUPABASE_KEY')
       );
       return $supabase->auth->getUser();
   });
   ```

## Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Supabase Documentation](https://supabase.io/docs)

## License

[MIT](LICENSE)

