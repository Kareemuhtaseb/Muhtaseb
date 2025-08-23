# Monthly Property Reports System

## Overview

The Monthly Property Reports System automatically generates comprehensive PDF reports for all property owners and sends them via email. Each report includes detailed financial information, property performance metrics, maintenance summaries, and occupancy data.

## Features

### 📊 Comprehensive Financial Analysis
- Total portfolio income and expenses
- Net income calculations
- Owner share calculations based on ownership percentages
- Distribution tracking and balance owed
- Category-wise income and expense breakdowns

### 🏢 Property Performance Details
- Individual property performance metrics
- Occupancy rates and unit status
- ROI calculations per property
- Detailed transaction lists

### 🔧 Maintenance Tracking
- Maintenance request summaries
- Status and priority breakdowns
- Property-specific maintenance details

### 📧 Automated Email Delivery
- Professional email templates
- PDF attachments with detailed reports
- Scheduled monthly delivery
- Manual trigger capability

## System Components

### 1. Services

#### MonthlySummaryService (`app/Services/MonthlySummaryService.php`)
- Generates comprehensive owner summaries
- Aggregates data across multiple properties
- Calculates financial metrics and ownership shares
- Groups transactions by category

#### PdfGenerationService (`app/Services/PdfGenerationService.php`)
- Creates professional PDF reports
- Handles multiple report types
- Configures PDF settings and formatting

### 2. Jobs

#### SendMonthlyOwnerReports (`app/Jobs/SendMonthlyOwnerReports.php`)
- Queued job for sending reports
- Processes all eligible owners
- Handles email delivery with PDF attachments
- Includes error handling and logging

### 3. Controllers

#### MonthlyReportController (`app/Http/Controllers/MonthlyReportController.php`)
- API endpoints for report management
- Preview and download functionality
- Manual report generation
- Owner eligibility checking

### 4. Commands

#### SendMonthlyReports (`app/Console/Commands/SendMonthlyReports.php`)
- Artisan command for manual report generation
- Supports custom month/year parameters
- Interactive confirmation prompts

## Installation & Setup

### 1. Install Dependencies
```bash
composer require barryvdh/laravel-dompdf
```

### 2. Configure Email Settings
Add to your `.env` file:
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Property Management System"

QUEUE_CONNECTION=redis
```

### 3. Run Queue Worker
```bash
php artisan queue:work
```

### 4. Test the System
```bash
php test_monthly_reports.php
```

## Usage

### Automated Monthly Reports

The system automatically sends reports on the 1st of each month at 9:00 AM. This is configured in `app/Console/Kernel.php`.

### Manual Report Generation

#### Via Artisan Command
```bash
# Send reports for previous month
php artisan reports:send-monthly

# Send reports for specific month
php artisan reports:send-monthly --month=7 --year=2025

# Force send without confirmation
php artisan reports:send-monthly --force
```

#### Via API Endpoints

```bash
# Send monthly reports to all owners
POST /api/monthly-reports/send
{
    "month": 7,
    "year": 2025
}

# Get eligible owners list
GET /api/monthly-reports/eligible-owners

# Preview owner report
GET /api/monthly-reports/owner/{owner_id}/preview?month=7&year=2025

# Download owner PDF
GET /api/monthly-reports/owner/{owner_id}/pdf?month=7&year=2025

# Get all owners summary
GET /api/monthly-reports/all-owners-summary?month=7&year=2025
```

## Report Content

### Executive Summary
- Financial overview with income, expenses, and net income
- Owner share calculations
- Distribution tracking
- Portfolio overview with property and unit counts

### Property Performance Details
- Individual property financial performance
- Ownership percentages
- Occupancy rates
- ROI calculations

### Income & Expense Breakdowns
- Category-wise transaction summaries
- Transaction counts and totals
- Detailed transaction lists per property

### Maintenance Summary
- Request status breakdowns
- Priority level summaries
- Property-specific maintenance details

### Distribution Details
- Owner distribution history
- Payment dates and amounts
- Balance owed calculations

## Email Templates

### PDF Report Template (`resources/views/emails/owner-monthly-report.blade.php`)
- Professional PDF layout with comprehensive styling
- Color-coded financial information
- Detailed tables and summaries
- Page breaks for property-specific sections

### Email Template (`resources/views/emails/owner-monthly-report-email.blade.php`)
- Professional email layout
- Quick summary of key metrics
- Clear call-to-action
- Contact information

## Configuration

### Scheduling
Modify the schedule in `app/Console/Kernel.php`:
```php
$schedule->command('reports:send-monthly')
         ->monthlyOn(1, '09:00')  // Change date and time as needed
         ->withoutOverlapping()
         ->runInBackground();
```

### Email Settings
Configure email settings in `config/mail.php` and `.env` file.

### Queue Settings
Configure queue settings in `config/queue.php` and `.env` file.

## Troubleshooting

### Common Issues

1. **PDF Generation Fails**
   - Ensure DomPDF is properly installed
   - Check PHP memory limits
   - Verify template syntax

2. **Email Delivery Fails**
   - Check SMTP configuration
   - Verify email credentials
   - Check queue worker status

3. **No Data in Reports**
   - Ensure owners have active properties
   - Check property-owner relationships
   - Verify financial data exists for the specified month

### Debugging

1. **Test Individual Components**
```bash
php test_monthly_reports.php
```

2. **Check Queue Status**
```bash
php artisan queue:work --verbose
```

3. **View Logs**
```bash
tail -f storage/logs/laravel.log
```

## API Documentation

### Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/monthly-reports/send` | Send reports to all owners |
| GET | `/api/monthly-reports/eligible-owners` | Get list of eligible owners |
| GET | `/api/monthly-reports/all-owners-summary` | Get summary for all owners |
| GET | `/api/monthly-reports/owner/{id}/preview` | Preview owner report |
| GET | `/api/monthly-reports/owner/{id}/pdf` | Download owner PDF |

### Request Parameters

#### Send Reports
```json
{
    "month": 7,
    "year": 2025
}
```

#### Preview/Download Reports
```
?month=7&year=2025
```

### Response Format
```json
{
    "success": true,
    "message": "Monthly reports job has been queued",
    "month": 7,
    "year": 2025,
    "month_name": "July 2025"
}
```

## Security Considerations

- All endpoints require authentication
- Input validation on all parameters
- File downloads are properly secured
- Email addresses are validated before sending

## Performance Optimization

- Reports are generated in background jobs
- PDF generation is optimized for large datasets
- Database queries are optimized with eager loading
- Queue system handles high-volume processing

## Future Enhancements

- Custom report templates
- Additional export formats (Excel, CSV)
- Report scheduling customization
- Advanced filtering options
- Report comparison features
