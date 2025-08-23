# Commercial Complex Data Structure

## Overview

This document describes the new data structure designed specifically for commercial complex management, based on your Excel file analysis. The structure is optimized for contract-based relationships between companies and shops/units.

## Key Differences from Previous Structure

### 1. **Contract-Centric Approach**
- **Before**: Tenant management with separate lease agreements
- **Now**: Direct contracts linking companies to shops with built-in rent calculations

### 2. **Company-Based Instead of Tenant-Based**
- **Before**: Individual tenant management
- **Now**: Company management with multiple contracts possible

### 3. **Simplified Income Tracking**
- **Before**: Complex payment and invoice systems
- **Now**: Direct monthly income tracking with transaction history

## Database Schema

### 1. Commercial Complex (`commercial_complexes`)
```sql
- id (Primary Key)
- name (e.g., "Mojama Commercial Complex")
- description
- address
- contact_person
- contact_phone
- contact_email
- timestamps
```

### 2. Shops (`shops`)
```sql
- id (Primary Key)
- commercial_complex_id (Foreign Key)
- shop_number (e.g., "1+2", "3", "4", "6+7+8")
- shop_name (e.g., "Chocolography", "Sukaina", "Original Burger")
- area (in square meters)
- location_description
- status (available, occupied, maintenance, reserved)
- timestamps
```

### 3. Companies (`companies`)
```sql
- id (Primary Key)
- name (Company name)
- business_number (Commercial registration)
- contact_person
- contact_phone
- contact_email
- address
- business_type (e.g., "Restaurant", "Retail", "Office")
- timestamps
```

### 4. Contracts (`contracts`) - **Main Table**
```sql
- id (Primary Key)
- shop_id (Foreign Key)
- company_id (Foreign Key)
- start_date
- end_date
- yearly_rent
- yearly_rent_with_tax
- yearly_services
- monthly_rent (Auto-calculated: yearly_rent / 12)
- monthly_rent_with_tax (Auto-calculated: yearly_rent_with_tax / 12)
- monthly_services (Auto-calculated: yearly_services / 12)
- status (active, expired, terminated, pending)
- notes
- timestamps
```

### 5. Monthly Income (`monthly_incomes`)
```sql
- id (Primary Key)
- contract_id (Foreign Key)
- year
- month (1-12)
- expected_amount
- actual_amount
- payment_date
- status (pending, paid, partial, overdue)
- notes
- timestamps
```

### 6. Income Transactions (`income_transactions`)
```sql
- id (Primary Key)
- contract_id (Foreign Key)
- amount
- transaction_date
- description
- type (rent, services, penalty, other)
- payment_method (cash, bank_transfer, check, card)
- reference_number
- notes
- timestamps
```

### 7. Complex Expenses (`complex_expenses`)
```sql
- id (Primary Key)
- commercial_complex_id (Foreign Key)
- category (e.g., "Maintenance", "Utilities", "Insurance")
- description
- amount
- expense_date
- vendor
- invoice_number
- payment_method
- notes
- timestamps
```

### 8. Monthly Summaries (`monthly_summaries`)
```sql
- id (Primary Key)
- commercial_complex_id (Foreign Key)
- year
- month
- total_expected_income
- total_actual_income
- total_expenses
- net_income
- active_contracts_count
- paid_contracts_count
- overdue_contracts_count
- timestamps
```

## Key Features

### 1. **Automatic Calculations**
- Monthly amounts are automatically calculated from yearly amounts
- Overdue amounts are calculated based on expected vs actual income
- Net income calculations include expenses

### 2. **Contract Management**
- Easy contract renewal and termination
- Historical contract tracking
- Active contract identification

### 3. **Income Tracking**
- Monthly expected vs actual income comparison
- Detailed transaction history
- Payment method tracking

### 4. **Reporting**
- Monthly summaries with key metrics
- Overdue contract identification
- Financial performance tracking

## API Endpoints

### Commercial Complex Management
- `GET /api/commercial-complexes` - List all complexes
- `POST /api/commercial-complexes` - Create new complex
- `GET /api/commercial-complexes/{id}` - Get complex details
- `PUT /api/commercial-complexes/{id}` - Update complex
- `DELETE /api/commercial-complexes/{id}` - Delete complex
- `POST /api/commercial-complexes/{id}/import-excel` - Import from Excel
- `GET /api/commercial-complexes/{id}/dashboard` - Dashboard data

### Shop Management
- `GET /api/shops` - List all shops
- `POST /api/shops` - Create new shop
- `GET /api/shops/{id}` - Get shop details
- `PUT /api/shops/{id}` - Update shop
- `DELETE /api/shops/{id}` - Delete shop
- `GET /api/shops/{id}/contracts` - Get shop contracts

### Company Management
- `GET /api/companies` - List all companies
- `POST /api/companies` - Create new company
- `GET /api/companies/{id}` - Get company details
- `PUT /api/companies/{id}` - Update company
- `DELETE /api/companies/{id}` - Delete company
- `GET /api/companies/{id}/contracts` - Get company contracts

### Contract Management
- `GET /api/contracts` - List all contracts
- `POST /api/contracts` - Create new contract
- `GET /api/contracts/{id}` - Get contract details
- `PUT /api/contracts/{id}` - Update contract
- `DELETE /api/contracts/{id}` - Delete contract
- `POST /api/contracts/{id}/renew` - Renew contract
- `POST /api/contracts/{id}/terminate` - Terminate contract

### Income Management
- `GET /api/monthly-incomes` - List monthly incomes
- `POST /api/monthly-incomes` - Create monthly income
- `GET /api/monthly-incomes/by-month/{year}/{month}` - Get by month
- `GET /api/income-transactions` - List income transactions
- `POST /api/income-transactions` - Create income transaction
- `GET /api/income-transactions/by-date-range` - Get by date range

### Expense Management
- `GET /api/complex-expenses` - List complex expenses
- `POST /api/complex-expenses` - Create expense
- `GET /api/complex-expenses/by-category` - Get by category

## Excel Import Structure

The system can import data from your Excel file with the following structure:

### "2024 Income" Sheet
- Shop Number (e.g., "1+2", "3", "4")
- Shop Name (e.g., "Chocolography", "Sukaina")
- Monthly income columns (January, February, etc.)

### "Contracts" Sheet
- Shop Number
- Yearly Rent
- Yearly Rent + Tax
- Yearly Services
- Start Date
- End Date
- Period (1 = active, 0 = expired)

### "Eyad Income" Sheet
- Amount
- Date
- Description
- Balance

## Benefits of This Structure

1. **Simplified Management**: No complex tenant-lease relationships
2. **Direct Contract Tracking**: Companies directly linked to shops
3. **Automatic Calculations**: Monthly amounts calculated from yearly amounts
4. **Better Reporting**: Built-in monthly summaries and overdue tracking
5. **Excel Compatibility**: Easy import from your existing Excel structure
6. **Scalability**: Can handle multiple commercial complexes
7. **Financial Tracking**: Comprehensive income and expense tracking

## Migration from Excel

The system includes an Excel import service that can:
1. Create commercial complex from your data
2. Import shops with their names and numbers
3. Create companies based on shop names
4. Import contracts with all financial details
5. Import income transactions
6. Generate monthly summaries

This structure is specifically designed to match your Excel calculations and provide a robust foundation for commercial complex management.
