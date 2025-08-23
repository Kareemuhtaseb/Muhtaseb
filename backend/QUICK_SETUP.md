# Quick Test Data Setup

## Option 1: Import SQL File (Easiest)

1. **Create a MySQL database** (if you don't have one):
   ```bash
   mysql -u root -p
   CREATE DATABASE muhtaseb_test;
   ```

2. **Import the test data**:
   ```bash
   mysql -u root -p muhtaseb_test < muhtaseb_test_data.sql
   ```

3. **Update your .env file** to point to this database:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=muhtaseb_test
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

4. **Start your application**:
   ```bash
   php artisan serve
   ```

## Option 2: Use Laravel Seeders

If you prefer to use Laravel's seeding system:

1. **Run migrations and seeders**:
   ```bash
   php artisan migrate:fresh --seed
   ```

## Test Data Summary

### Login Credentials
- **Admin**: `admin@muhtaseb.com` / `password`
- **Manager**: `manager@muhtaseb.com` / `password`
- **Viewer**: `viewer@muhtaseb.com` / `password`

### Sample Data
- **3 Properties**: Sunset Apartments, Downtown Office Plaza, Riverside Retail Center
- **44 Units**: Mix of residential, office, and retail units
- **4 Tenants**: Alice Wilson, David Chen, TechStart Inc., Fashion Boutique LLC
- **27 Active Leases**: With realistic terms and conditions
- **Income & Expenses**: 6 months of financial data
- **Invoices & Payments**: Current and pending invoices
- **Maintenance Requests**: Various priority levels and statuses
- **Notifications**: System alerts and messages

### What You Can Test
- ✅ Property management and unit tracking
- ✅ Tenant profiles and lease management
- ✅ Financial reporting (income vs expenses)
- ✅ Invoice generation and payment tracking
- ✅ Maintenance request workflows
- ✅ User role permissions (admin/manager/viewer)
- ✅ Dashboard analytics and reports

## Troubleshooting

### Database Connection Issues
- Make sure MySQL is running
- Check your database credentials in `.env`
- Ensure the database exists

### Import Issues
- If you get foreign key constraint errors, try importing with:
  ```bash
  mysql -u root -p muhtaseb_test --init-command="SET SESSION FOREIGN_KEY_CHECKS=0;" < muhtaseb_test_data.sql
  ```

### Laravel Issues
- Clear cache: `php artisan cache:clear`
- Clear config: `php artisan config:clear`
- Regenerate autoload: `composer dump-autoload`

---

**That's it!** You now have a fully populated test database to explore your Muhtaseb property management system. 🎉
