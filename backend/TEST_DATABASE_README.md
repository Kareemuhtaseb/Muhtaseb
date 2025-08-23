# Muhtaseb Test Database

This document provides information about the test database setup for the Muhtaseb property management system.

## Quick Setup

### Option 1: Automated Setup (Recommended)
```bash
cd backend
php setup_test_db.php
```

### Option 2: Manual Setup
```bash
cd backend
php artisan migrate:fresh --seed
```

## Test Data Overview

The test database includes comprehensive sample data to test all features of the property management system:

### Users & Authentication
- **3 Test Users** with different permission levels:
  - **Admin** (`admin@muhtaseb.com` / `password`) - Full system access
  - **Manager** (`manager@muhtaseb.com` / `password`) - Property management access
  - **Viewer** (`viewer@muhtaseb.com` / `password`) - Read-only access

### Properties
- **3 Sample Properties**:
  1. **Sunset Apartments** (Los Angeles) - 24 units, 20 occupied
  2. **Downtown Office Plaza** (New York) - 12 units, 10 occupied  
  3. **Riverside Retail Center** (Chicago) - 8 units, 7 occupied

### Units & Tenants
- **44 Total Units** across all properties
- **4 Sample Tenants** with realistic profiles:
  - Alice Wilson (Residential tenant)
  - David Chen (Residential tenant)
  - TechStart Inc. (Commercial tenant)
  - Fashion Boutique LLC (Retail tenant)

### Financial Data
- **6 Months of Income Data** - Rent payments, late fees, etc.
- **6 Months of Expense Data** - Utilities, maintenance, taxes, etc.
- **Realistic Financial Calculations** based on property size and type

### Business Operations
- **Active Leases** with varying terms and conditions
- **Invoices** for the last 3 months
- **Payment Records** with different payment methods
- **Maintenance Requests** with various priorities and statuses
- **Notifications** for different system events

## Database Schema

### Core Entities
- **Properties** - Real estate assets with detailed information
- **Units** - Individual spaces within properties
- **Tenants** - People/businesses renting units
- **Owners** - Property owners and investors
- **Leases** - Rental agreements between tenants and properties

### Financial Tracking
- **Income** - All revenue streams (rent, fees, etc.)
- **Expenses** - All property costs (utilities, maintenance, etc.)
- **Invoices** - Bills sent to tenants
- **Payments** - Money received from tenants
- **Categories** - Classification for income and expenses

### Operations
- **Maintenance Requests** - Service requests and repairs
- **Notifications** - System alerts and messages
- **Audit Logs** - Activity tracking for compliance

## Sample Data Details

### Property Types
- Apartment Complex
- Office Building
- Retail Space
- Warehouse
- Residential House

### Unit Types
- Studio
- 1 Bedroom
- 2 Bedroom
- 3 Bedroom
- Office Suite
- Retail Unit
- Warehouse Space

### Income Categories
- Rent
- Late Fees
- Application Fees
- Pet Fees
- Other Income

### Expense Categories
- Electricity
- Water
- Gas
- Maintenance
- Repairs
- Property Tax
- Insurance
- Property Management
- Cleaning
- Landscaping
- Security
- Other Expenses

### Payment Methods
- Cash
- Bank Transfer
- Credit Card
- Check
- Online Payment

## Testing Scenarios

### Financial Reports
- Test income vs expense reports
- Verify property profitability calculations
- Check monthly and annual summaries

### Tenant Management
- View tenant profiles and lease information
- Test rent collection and late fee calculations
- Verify maintenance request workflows

### Property Operations
- Test unit availability and occupancy tracking
- Verify property maintenance scheduling
- Check notification systems

### User Permissions
- Test different user roles and access levels
- Verify admin vs manager vs viewer permissions
- Check data visibility restrictions

## Database Location

The SQLite database file is located at:
```
backend/database/database.sqlite
```

## Resetting the Database

To reset the test database with fresh data:

```bash
cd backend
php artisan migrate:fresh --seed
```

Or use the automated script:
```bash
cd backend
php setup_test_db.php
```

## Troubleshooting

### Common Issues

1. **Permission Denied**: Ensure the `database` directory is writable
2. **Migration Errors**: Check that all required PHP extensions are installed
3. **Seeding Errors**: Verify that all model relationships are properly defined

### Database Connection

The test database uses SQLite by default. To use a different database:

1. Update `.env` file with your database credentials
2. Run migrations: `php artisan migrate:fresh --seed`

### Performance

For large datasets, consider:
- Using MySQL or PostgreSQL instead of SQLite
- Adding database indexes for frequently queried fields
- Implementing pagination for large data sets

## Data Relationships

The test data maintains referential integrity with realistic relationships:

- Properties → Units → Tenants → Leases
- Income/Expenses → Properties → Categories
- Invoices → Tenants → Units → Leases
- Payments → Invoices → Tenants
- Maintenance Requests → Units → Tenants

## Customization

To modify the test data:

1. Edit `database/seeders/TestDataSeeder.php`
2. Adjust the data generation methods
3. Re-run the seeding process

## Support

For issues with the test database setup, check:
- Laravel documentation for database operations
- PHP error logs for detailed error messages
- Database file permissions and disk space

---

**Note**: This test database is for development and testing purposes only. Do not use this data in production environments.
