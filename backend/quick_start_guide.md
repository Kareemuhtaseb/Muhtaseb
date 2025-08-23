# Muhtaseb Quick Start Guide

## 🚨 Critical Issue: Database Drivers Missing

The main issue preventing the application from running is **missing PDO database drivers**. Here's how to fix it:

### Option 1: Install XAMPP (Recommended for Windows)

1. **Download XAMPP**: https://www.apachefriends.org/
2. **Install XAMPP** with default settings
3. **Start XAMPP Control Panel**
4. **Start Apache and MySQL services**
5. **Enable PDO extensions**:
   - Open `C:\xampp\php\php.ini`
   - Uncomment these lines (remove semicolon):
     ```ini
     extension=pdo
     extension=pdo_sqlite
     extension=pdo_mysql
     extension=sqlite3
     ```
   - Restart Apache in XAMPP Control Panel

### Option 2: Use Standalone PHP with Extensions

1. **Download PHP extensions** for your PHP version
2. **Install PDO drivers**:
   - `php_pdo_sqlite.dll`
   - `php_pdo_mysql.dll`
   - `php_sqlite3.dll`
3. **Update php.ini** to include extensions

### Option 3: Use Docker (Alternative)

```bash
# Use the provided docker-compose.yml
docker-compose up -d
```

## 🔧 Quick Setup After Driver Installation

### 1. Generate Application Key
```bash
cd backend
php artisan key:generate
```

### 2. Setup Database
```bash
# For MySQL (if using XAMPP)
php setup_mysql_test_db.php

# For SQLite (if PDO SQLite is available)
php setup_simple_db.php
```

### 3. Run Migrations
```bash
php artisan migrate
```

### 4. Seed Database
```bash
php artisan db:seed
```

### 5. Start Backend Server
```bash
php artisan serve
```

### 6. Start Frontend (in new terminal)
```bash
cd frontend
npm install
npm run dev
```

## 🧪 Test the Setup

### Backend API Test
```bash
# Test dashboard endpoint
curl http://localhost:8000/api/dashboard

# Test properties endpoint
curl http://localhost:8000/api/properties
```

### Frontend Test
1. Open browser to `http://localhost:5173`
2. Check browser console for API connection errors
3. Test dashboard loading

## 📊 Expected Results

After successful setup:

### Database Tables Created:
- `users` - User authentication
- `properties` - Property management
- `units` - Unit management
- `contracts` - Rental contracts
- `income` - Income tracking
- `expenses` - Expense tracking
- `owners` - Owner management
- `payments` - Payment tracking
- `invoices` - Invoice management
- `maintenance_requests` - Maintenance tracking
- `notifications` - System notifications
- `audit_logs` - Activity logging

### API Endpoints Available:
- `GET /api/dashboard` - Dashboard statistics
- `GET /api/properties` - List properties
- `POST /api/properties` - Create property
- `GET /api/units` - List units
- `GET /api/contracts` - List contracts
- `GET /api/income` - List income
- `GET /api/expenses` - List expenses
- `GET /api/owners` - List owners
- `GET /api/reports` - Financial reports

### Frontend Features:
- ✅ Dashboard with statistics
- ✅ Property management
- ✅ Unit management
- ✅ Contract management
- ✅ Income/Expense tracking
- ✅ Owner management
- ✅ Financial reports
- ✅ Maintenance requests
- ✅ Notifications

## 🔍 Troubleshooting

### Database Connection Issues
```bash
# Check PHP modules
php -m

# Check PDO drivers
php -r "print_r(PDO::getAvailableDrivers());"

# Test database connection
php artisan tinker
```

### API Connection Issues
```bash
# Check if backend is running
curl http://localhost:8000/api/dashboard

# Check CORS configuration
# Verify frontend URL in config/cors.php
```

### Frontend Issues
```bash
# Check if frontend is running
curl http://localhost:5173

# Check browser console for errors
# Verify API base URL in main.js
```

## 📞 Support

If you encounter issues:

1. **Check the debug report**: `DEBUG_REPORT.md`
2. **Run diagnostic script**: `php fix_database_issues.php`
3. **Verify PHP extensions**: `php -m`
4. **Check Laravel logs**: `storage/logs/laravel.log`

## 🎯 Next Steps

Once the application is running:

1. **Create admin user** through registration
2. **Add properties and units**
3. **Create contracts and tenants**
4. **Record income and expenses**
5. **Generate financial reports**
6. **Test all features**

## 📚 Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Vue.js Documentation](https://vuejs.org/guide/)
- [PHP PDO Documentation](https://www.php.net/manual/en/book.pdo.php)
- [XAMPP Documentation](https://www.apachefriends.org/docs.html)
