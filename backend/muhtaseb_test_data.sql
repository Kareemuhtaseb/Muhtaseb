-- Muhtaseb Test Database Dump
-- This file contains sample data for testing the property management system
-- Import this into your MySQL/MariaDB database to get test data

-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS `muhtaseb_test` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `muhtaseb_test`;

-- Users table
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `permissions`, `is_active`, `email_verified_at`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin@muhtaseb.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '["*"]', 1, NOW(), NOW(), NOW()),
(2, 'Manager User', 'manager@muhtaseb.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'manager', '["properties.view","properties.create","properties.edit","units.view","units.create","units.edit","tenants.view","tenants.create","tenants.edit","income.view","income.create","income.edit","expenses.view","expenses.create","expenses.edit"]', 1, NOW(), NOW(), NOW()),
(3, 'Viewer User', 'viewer@muhtaseb.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'viewer', '["properties.view","units.view","tenants.view","income.view","expenses.view","reports.view"]', 1, NOW(), NOW(), NOW());

-- Categories table
INSERT INTO `categories` (`id`, `type`, `name`, `created_at`, `updated_at`) VALUES
(1, 'income', 'Rent', NOW(), NOW()),
(2, 'income', 'Late Fees', NOW(), NOW()),
(3, 'income', 'Application Fees', NOW(), NOW()),
(4, 'income', 'Pet Fees', NOW(), NOW()),
(5, 'income', 'Other Income', NOW(), NOW()),
(6, 'expense', 'Electricity', NOW(), NOW()),
(7, 'expense', 'Water', NOW(), NOW()),
(8, 'expense', 'Gas', NOW(), NOW()),
(9, 'expense', 'Maintenance', NOW(), NOW()),
(10, 'expense', 'Repairs', NOW(), NOW()),
(11, 'expense', 'Property Tax', NOW(), NOW()),
(12, 'expense', 'Insurance', NOW(), NOW()),
(13, 'expense', 'Property Management', NOW(), NOW()),
(14, 'expense', 'Cleaning', NOW(), NOW()),
(15, 'expense', 'Landscaping', NOW(), NOW()),
(16, 'expense', 'Security', NOW(), NOW()),
(17, 'expense', 'Other Expenses', NOW(), NOW());

-- Property types table
INSERT INTO `property_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Apartment Complex', NOW(), NOW()),
(2, 'Office Building', NOW(), NOW()),
(3, 'Retail Space', NOW(), NOW()),
(4, 'Warehouse', NOW(), NOW()),
(5, 'Residential House', NOW(), NOW());

-- Unit types table
INSERT INTO `unit_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Studio', NOW(), NOW()),
(2, '1 Bedroom', NOW(), NOW()),
(3, '2 Bedroom', NOW(), NOW()),
(4, '3 Bedroom', NOW(), NOW()),
(5, 'Office Suite', NOW(), NOW()),
(6, 'Retail Unit', NOW(), NOW()),
(7, 'Warehouse Space', NOW(), NOW());

-- Payment methods table
INSERT INTO `payment_methods` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Cash', NOW(), NOW()),
(2, 'Bank Transfer', NOW(), NOW()),
(3, 'Credit Card', NOW(), NOW()),
(4, 'Check', NOW(), NOW()),
(5, 'Online Payment', NOW(), NOW());

-- Owners table
INSERT INTO `owners` (`id`, `name`, `email`, `phone`, `address`, `tax_id`, `bank_account`, `ownership_percentage`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'John Smith', 'john.smith@email.com', '+1-555-0101', '123 Main St, City, State 12345', 'TAX123456789', '1234567890', 100, 1, NOW(), NOW()),
(2, 'Sarah Johnson', 'sarah.johnson@email.com', '+1-555-0102', '456 Oak Ave, City, State 12345', 'TAX987654321', '0987654321', 100, 1, NOW(), NOW()),
(3, 'Michael Brown', 'michael.brown@email.com', '+1-555-0103', '789 Pine Rd, City, State 12345', 'TAX456789123', '1122334455', 100, 1, NOW(), NOW());

-- Properties table
INSERT INTO `properties` (`id`, `name`, `address`, `property_type_id`, `total_units`, `occupied_units`, `total_area`, `year_built`, `purchase_price`, `current_value`, `monthly_rent_income`, `monthly_expenses`, `property_tax_rate`, `insurance_rate`, `management_fee_rate`, `is_active`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Sunset Apartments', '100 Sunset Blvd, Los Angeles, CA 90210', 1, 24, 20, 24000, 2015, 2500000, 3200000, 48000, 12000, 1.2, 0.5, 8.0, 1, 'Modern apartment complex with amenities', NOW(), NOW()),
(2, 'Downtown Office Plaza', '500 Business Ave, New York, NY 10001', 2, 12, 10, 50000, 2010, 5000000, 6500000, 125000, 25000, 1.5, 0.8, 10.0, 1, 'Premium office space in downtown', NOW(), NOW()),
(3, 'Riverside Retail Center', '300 Commerce St, Chicago, IL 60601', 3, 8, 7, 15000, 2018, 1800000, 2200000, 35000, 8000, 1.1, 0.6, 8.5, 1, 'Shopping center with multiple retail units', NOW(), NOW());

-- Units table (sample units for each property)
INSERT INTO `units` (`id`, `property_id`, `unit_number`, `unit_type_id`, `floor_number`, `area`, `bedrooms`, `bathrooms`, `monthly_rent`, `security_deposit`, `is_occupied`, `is_active`, `description`, `created_at`, `updated_at`) VALUES
-- Sunset Apartments Units (24 units)
(1, 1, 'Sunset Apartments - Unit 1', 2, 1, 850, 1, 1, 1500, 1500, 1, 1, '1 Bedroom unit with modern amenities', NOW(), NOW()),
(2, 1, 'Sunset Apartments - Unit 2', 3, 1, 1100, 2, 2, 2000, 2000, 1, 1, '2 Bedroom unit with modern amenities', NOW(), NOW()),
(3, 1, 'Sunset Apartments - Unit 3', 1, 1, 650, 0, 1, 1200, 1200, 1, 1, 'Studio unit with modern amenities', NOW(), NOW()),
(4, 1, 'Sunset Apartments - Unit 4', 4, 2, 1400, 3, 2, 2800, 2800, 1, 1, '3 Bedroom unit with modern amenities', NOW(), NOW()),
(5, 1, 'Sunset Apartments - Unit 5', 2, 2, 900, 1, 1, 1600, 1600, 1, 1, '1 Bedroom unit with modern amenities', NOW(), NOW()),
(6, 1, 'Sunset Apartments - Unit 6', 3, 2, 1200, 2, 2, 2100, 2100, 1, 1, '2 Bedroom unit with modern amenities', NOW(), NOW()),
(7, 1, 'Sunset Apartments - Unit 7', 1, 3, 700, 0, 1, 1300, 1300, 1, 1, 'Studio unit with modern amenities', NOW(), NOW()),
(8, 1, 'Sunset Apartments - Unit 8', 2, 3, 950, 1, 1, 1700, 1700, 1, 1, '1 Bedroom unit with modern amenities', NOW(), NOW()),
(9, 1, 'Sunset Apartments - Unit 9', 3, 3, 1150, 2, 2, 2200, 2200, 1, 1, '2 Bedroom unit with modern amenities', NOW(), NOW()),
(10, 1, 'Sunset Apartments - Unit 10', 4, 3, 1500, 3, 2, 2900, 2900, 1, 1, '3 Bedroom unit with modern amenities', NOW(), NOW()),
(11, 1, 'Sunset Apartments - Unit 11', 2, 4, 880, 1, 1, 1550, 1550, 1, 1, '1 Bedroom unit with modern amenities', NOW(), NOW()),
(12, 1, 'Sunset Apartments - Unit 12', 3, 4, 1180, 2, 2, 2150, 2150, 1, 1, '2 Bedroom unit with modern amenities', NOW(), NOW()),
(13, 1, 'Sunset Apartments - Unit 13', 1, 4, 680, 0, 1, 1250, 1250, 1, 1, 'Studio unit with modern amenities', NOW(), NOW()),
(14, 1, 'Sunset Apartments - Unit 14', 2, 5, 920, 1, 1, 1650, 1650, 1, 1, '1 Bedroom unit with modern amenities', NOW(), NOW()),
(15, 1, 'Sunset Apartments - Unit 15', 3, 5, 1250, 2, 2, 2250, 2250, 1, 1, '2 Bedroom unit with modern amenities', NOW(), NOW()),
(16, 1, 'Sunset Apartments - Unit 16', 4, 5, 1600, 3, 2, 3000, 3000, 1, 1, '3 Bedroom unit with modern amenities', NOW(), NOW()),
(17, 1, 'Sunset Apartments - Unit 17', 2, 1, 870, 1, 1, 1520, 1520, 1, 1, '1 Bedroom unit with modern amenities', NOW(), NOW()),
(18, 1, 'Sunset Apartments - Unit 18', 3, 1, 1120, 2, 2, 2050, 2050, 1, 1, '2 Bedroom unit with modern amenities', NOW(), NOW()),
(19, 1, 'Sunset Apartments - Unit 19', 1, 2, 670, 0, 1, 1280, 1280, 1, 1, 'Studio unit with modern amenities', NOW(), NOW()),
(20, 1, 'Sunset Apartments - Unit 20', 2, 2, 930, 1, 1, 1680, 1680, 1, 1, '1 Bedroom unit with modern amenities', NOW(), NOW()),
(21, 1, 'Sunset Apartments - Unit 21', 3, 3, 1280, 2, 2, 2300, 2300, 0, 1, '2 Bedroom unit with modern amenities', NOW(), NOW()),
(22, 1, 'Sunset Apartments - Unit 22', 4, 3, 1550, 3, 2, 3100, 3100, 0, 1, '3 Bedroom unit with modern amenities', NOW(), NOW()),
(23, 1, 'Sunset Apartments - Unit 23', 2, 4, 890, 1, 1, 1580, 1580, 0, 1, '1 Bedroom unit with modern amenities', NOW(), NOW()),
(24, 1, 'Sunset Apartments - Unit 24', 3, 4, 1220, 2, 2, 2350, 2350, 0, 1, '2 Bedroom unit with modern amenities', NOW(), NOW()),

-- Downtown Office Plaza Units (12 units)
(25, 2, 'Downtown Office Plaza - Unit 1', 5, 1, 2000, 0, 1, 3000, 3000, 1, 1, 'Office Suite unit with modern amenities', NOW(), NOW()),
(26, 2, 'Downtown Office Plaza - Unit 2', 5, 1, 2500, 0, 1, 3500, 3500, 1, 1, 'Office Suite unit with modern amenities', NOW(), NOW()),
(27, 2, 'Downtown Office Plaza - Unit 3', 5, 2, 3000, 0, 1, 4000, 4000, 1, 1, 'Office Suite unit with modern amenities', NOW(), NOW()),
(28, 2, 'Downtown Office Plaza - Unit 4', 5, 2, 1800, 0, 1, 2800, 2800, 1, 1, 'Office Suite unit with modern amenities', NOW(), NOW()),
(29, 2, 'Downtown Office Plaza - Unit 5', 5, 3, 2200, 0, 1, 3200, 3200, 1, 1, 'Office Suite unit with modern amenities', NOW(), NOW()),
(30, 2, 'Downtown Office Plaza - Unit 6', 5, 3, 2800, 0, 1, 3800, 3800, 1, 1, 'Office Suite unit with modern amenities', NOW(), NOW()),
(31, 2, 'Downtown Office Plaza - Unit 7', 5, 4, 3500, 0, 1, 4500, 4500, 1, 1, 'Office Suite unit with modern amenities', NOW(), NOW()),
(32, 2, 'Downtown Office Plaza - Unit 8', 5, 4, 4000, 0, 1, 5000, 5000, 1, 1, 'Office Suite unit with modern amenities', NOW(), NOW()),
(33, 2, 'Downtown Office Plaza - Unit 9', 5, 5, 3200, 0, 1, 4200, 4200, 1, 1, 'Office Suite unit with modern amenities', NOW(), NOW()),
(34, 2, 'Downtown Office Plaza - Unit 10', 5, 5, 3800, 0, 1, 4800, 4800, 1, 1, 'Office Suite unit with modern amenities', NOW(), NOW()),
(35, 2, 'Downtown Office Plaza - Unit 11', 5, 1, 2600, 0, 1, 3600, 3600, 0, 1, 'Office Suite unit with modern amenities', NOW(), NOW()),
(36, 2, 'Downtown Office Plaza - Unit 12', 5, 2, 2400, 0, 1, 3400, 3400, 0, 1, 'Office Suite unit with modern amenities', NOW(), NOW()),

-- Riverside Retail Center Units (8 units)
(37, 3, 'Riverside Retail Center - Unit 1', 6, 1, 1500, 0, 1, 2500, 2500, 1, 1, 'Retail Unit with modern amenities', NOW(), NOW()),
(38, 3, 'Riverside Retail Center - Unit 2', 6, 1, 2000, 0, 1, 3000, 3000, 1, 1, 'Retail Unit with modern amenities', NOW(), NOW()),
(39, 3, 'Riverside Retail Center - Unit 3', 6, 1, 1800, 0, 1, 2800, 2800, 1, 1, 'Retail Unit with modern amenities', NOW(), NOW()),
(40, 3, 'Riverside Retail Center - Unit 4', 6, 1, 2200, 0, 1, 3200, 3200, 1, 1, 'Retail Unit with modern amenities', NOW(), NOW()),
(41, 3, 'Riverside Retail Center - Unit 5', 6, 1, 1600, 0, 1, 2600, 2600, 1, 1, 'Retail Unit with modern amenities', NOW(), NOW()),
(42, 3, 'Riverside Retail Center - Unit 6', 6, 1, 1900, 0, 1, 2900, 2900, 1, 1, 'Retail Unit with modern amenities', NOW(), NOW()),
(43, 3, 'Riverside Retail Center - Unit 7', 6, 1, 2100, 0, 1, 3100, 3100, 1, 1, 'Retail Unit with modern amenities', NOW(), NOW()),
(44, 3, 'Riverside Retail Center - Unit 8', 6, 1, 1700, 0, 1, 2700, 2700, 0, 1, 'Retail Unit with modern amenities', NOW(), NOW());

-- Tenants table
INSERT INTO `tenants` (`id`, `name`, `email`, `phone`, `address`, `emergency_contact`, `emergency_phone`, `employment_status`, `monthly_income`, `credit_score`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Alice Wilson', 'alice.wilson@email.com', '+1-555-0201', '100 Sunset Blvd, Unit 1A, Los Angeles, CA 90210', 'Bob Wilson', '+1-555-0202', 'Employed', 4500, 750, 1, NOW(), NOW()),
(2, 'David Chen', 'david.chen@email.com', '+1-555-0203', '100 Sunset Blvd, Unit 2B, Los Angeles, CA 90210', 'Lisa Chen', '+1-555-0204', 'Self-Employed', 6000, 780, 1, NOW(), NOW()),
(3, 'TechStart Inc.', 'office@techstart.com', '+1-555-0205', '500 Business Ave, Suite 301, New York, NY 10001', 'CEO Office', '+1-555-0206', 'Business', 50000, 800, 1, NOW(), NOW()),
(4, 'Fashion Boutique LLC', 'contact@fashionboutique.com', '+1-555-0207', '300 Commerce St, Unit 5, Chicago, IL 60601', 'Store Manager', '+1-555-0208', 'Business', 25000, 720, 1, NOW(), NOW());

-- Leases table (for occupied units)
INSERT INTO `leases` (`id`, `unit_id`, `tenant_id`, `start_date`, `end_date`, `monthly_rent`, `security_deposit`, `late_fee`, `grace_period_days`, `is_active`, `terms`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2024-01-01', '2025-12-31', 1500, 1500, 50, 5, 1, 'Standard lease agreement with standard terms and conditions.', NOW(), NOW()),
(2, 2, 2, '2024-02-01', '2025-01-31', 2000, 2000, 50, 5, 1, 'Standard lease agreement with standard terms and conditions.', NOW(), NOW()),
(3, 3, 1, '2024-03-01', '2025-02-28', 1200, 1200, 50, 5, 1, 'Standard lease agreement with standard terms and conditions.', NOW(), NOW()),
(4, 4, 2, '2024-04-01', '2025-03-31', 2800, 2800, 50, 5, 1, 'Standard lease agreement with standard terms and conditions.', NOW(), NOW()),
(5, 5, 1, '2024-05-01', '2025-04-30', 1600, 1600, 50, 5, 1, 'Standard lease agreement with standard terms and conditions.', NOW(), NOW()),
(6, 6, 2, '2024-06-01', '2025-05-31', 2100, 2100, 50, 5, 1, 'Standard lease agreement with standard terms and conditions.', NOW(), NOW()),
(7, 7, 1, '2024-07-01', '2025-06-30', 1300, 1300, 50, 5, 1, 'Standard lease agreement with standard terms and conditions.', NOW(), NOW()),
(8, 8, 2, '2024-08-01', '2025-07-31', 1700, 1700, 50, 5, 1, 'Standard lease agreement with standard terms and conditions.', NOW(), NOW()),
(9, 9, 1, '2024-09-01', '2025-08-31', 2200, 2200, 50, 5, 1, 'Standard lease agreement with standard terms and conditions.', NOW(), NOW()),
(10, 10, 2, '2024-10-01', '2025-09-30', 2900, 2900, 50, 5, 1, 'Standard lease agreement with standard terms and conditions.', NOW(), NOW()),
(11, 25, 3, '2024-01-15', '2025-01-14', 3000, 3000, 100, 5, 1, 'Standard lease agreement with standard terms and conditions.', NOW(), NOW()),
(12, 26, 3, '2024-02-15', '2025-02-14', 3500, 3500, 100, 5, 1, 'Standard lease agreement with standard terms and conditions.', NOW(), NOW()),
(13, 27, 3, '2024-03-15', '2025-03-14', 4000, 4000, 100, 5, 1, 'Standard lease agreement with standard terms and conditions.', NOW(), NOW()),
(14, 28, 3, '2024-04-15', '2025-04-14', 2800, 2800, 100, 5, 1, 'Standard lease agreement with standard terms and conditions.', NOW(), NOW()),
(15, 29, 3, '2024-05-15', '2025-05-14', 3200, 3200, 100, 5, 1, 'Standard lease agreement with standard terms and conditions.', NOW(), NOW()),
(16, 30, 3, '2024-06-15', '2025-06-14', 3800, 3800, 100, 5, 1, 'Standard lease agreement with standard terms and conditions.', NOW(), NOW()),
(17, 31, 3, '2024-07-15', '2025-07-14', 4500, 4500, 100, 5, 1, 'Standard lease agreement with standard terms and conditions.', NOW(), NOW()),
(18, 32, 3, '2024-08-15', '2025-08-14', 5000, 5000, 100, 5, 1, 'Standard lease agreement with standard terms and conditions.', NOW(), NOW()),
(19, 33, 3, '2024-09-15', '2025-09-14', 4200, 4200, 100, 5, 1, 'Standard lease agreement with standard terms and conditions.', NOW(), NOW()),
(20, 34, 3, '2024-10-15', '2025-10-14', 4800, 4800, 100, 5, 1, 'Standard lease agreement with standard terms and conditions.', NOW(), NOW()),
(21, 37, 4, '2024-01-10', '2025-01-09', 2500, 2500, 75, 5, 1, 'Standard lease agreement with standard terms and conditions.', NOW(), NOW()),
(22, 38, 4, '2024-02-10', '2025-02-09', 3000, 3000, 75, 5, 1, 'Standard lease agreement with standard terms and conditions.', NOW(), NOW()),
(23, 39, 4, '2024-03-10', '2025-03-09', 2800, 2800, 75, 5, 1, 'Standard lease agreement with standard terms and conditions.', NOW(), NOW()),
(24, 40, 4, '2024-04-10', '2025-04-09', 3200, 3200, 75, 5, 1, 'Standard lease agreement with standard terms and conditions.', NOW(), NOW()),
(25, 41, 4, '2024-05-10', '2025-05-09', 2600, 2600, 75, 5, 1, 'Standard lease agreement with standard terms and conditions.', NOW(), NOW()),
(26, 42, 4, '2024-06-10', '2025-06-09', 2900, 2900, 75, 5, 1, 'Standard lease agreement with standard terms and conditions.', NOW(), NOW()),
(27, 43, 4, '2024-07-10', '2025-07-09', 3100, 3100, 75, 5, 1, 'Standard lease agreement with standard terms and conditions.', NOW(), NOW());

-- Income records (last 6 months)
INSERT INTO `income` (`id`, `property_id`, `unit_id`, `tenant_id`, `category_id`, `amount`, `date`, `description`, `payment_method_id`, `is_recurring`, `created_at`, `updated_at`) VALUES
-- Recent income records
(1, 1, 1, 1, 1, 1500, '2024-12-01', 'Monthly rent payment', 2, 1, NOW(), NOW()),
(2, 1, 2, 2, 1, 2000, '2024-12-01', 'Monthly rent payment', 3, 1, NOW(), NOW()),
(3, 1, 3, 1, 1, 1200, '2024-12-01', 'Monthly rent payment', 2, 1, NOW(), NOW()),
(4, 1, 4, 2, 1, 2800, '2024-12-01', 'Monthly rent payment', 3, 1, NOW(), NOW()),
(5, 1, 5, 1, 1, 1600, '2024-12-01', 'Monthly rent payment', 2, 1, NOW(), NOW()),
(6, 2, 25, 3, 1, 3000, '2024-12-01', 'Monthly rent payment', 2, 1, NOW(), NOW()),
(7, 2, 26, 3, 1, 3500, '2024-12-01', 'Monthly rent payment', 3, 1, NOW(), NOW()),
(8, 2, 27, 3, 1, 4000, '2024-12-01', 'Monthly rent payment', 2, 1, NOW(), NOW()),
(9, 3, 37, 4, 1, 2500, '2024-12-01', 'Monthly rent payment', 3, 1, NOW(), NOW()),
(10, 3, 38, 4, 1, 3000, '2024-12-01', 'Monthly rent payment', 2, 1, NOW(), NOW()),
-- Previous month
(11, 1, 1, 1, 1, 1500, '2024-11-01', 'Monthly rent payment', 2, 1, NOW(), NOW()),
(12, 1, 2, 2, 1, 2000, '2024-11-01', 'Monthly rent payment', 3, 1, NOW(), NOW()),
(13, 2, 25, 3, 1, 3000, '2024-11-01', 'Monthly rent payment', 2, 1, NOW(), NOW()),
(14, 3, 37, 4, 1, 2500, '2024-11-01', 'Monthly rent payment', 3, 1, NOW(), NOW()),
-- Late fees
(15, 1, 1, 1, 2, 50, '2024-12-05', 'Late payment fee', 2, 0, NOW(), NOW()),
(16, 2, 25, 3, 2, 100, '2024-11-10', 'Late payment fee', 3, 0, NOW(), NOW());

-- Expense records (last 6 months)
INSERT INTO `expenses` (`id`, `property_id`, `category_id`, `amount`, `date`, `description`, `vendor`, `is_recurring`, `payment_method_id`, `created_at`, `updated_at`) VALUES
-- Recent expenses
(1, 1, 6, 800, '2024-12-01', 'Monthly electricity bill for common areas and units', 'City Power Company', 1, 2, NOW(), NOW()),
(2, 1, 7, 300, '2024-12-01', 'Water and sewer charges for the property', 'Municipal Water Department', 1, 2, NOW(), NOW()),
(3, 1, 8, 200, '2024-12-01', 'Natural gas utility charges', 'Gas Utility Co.', 1, 2, NOW(), NOW()),
(4, 1, 9, 500, '2024-12-01', 'Regular maintenance and upkeep services', 'ABC Maintenance Services', 0, 2, NOW(), NOW()),
(5, 2, 6, 1200, '2024-12-01', 'Monthly electricity bill for common areas and units', 'City Power Company', 1, 2, NOW(), NOW()),
(6, 2, 7, 450, '2024-12-01', 'Water and sewer charges for the property', 'Municipal Water Department', 1, 2, NOW(), NOW()),
(7, 2, 13, 1250, '2024-12-01', 'Property management fees', 'Professional Property Management', 1, 2, NOW(), NOW()),
(8, 3, 6, 600, '2024-12-01', 'Monthly electricity bill for common areas and units', 'City Power Company', 1, 2, NOW(), NOW()),
(9, 3, 7, 250, '2024-12-01', 'Water and sewer charges for the property', 'Municipal Water Department', 1, 2, NOW(), NOW()),
(10, 3, 9, 400, '2024-12-01', 'Regular maintenance and upkeep services', 'ABC Maintenance Services', 0, 2, NOW(), NOW()),
-- Previous month
(11, 1, 6, 750, '2024-11-01', 'Monthly electricity bill for common areas and units', 'City Power Company', 1, 2, NOW(), NOW()),
(12, 1, 7, 280, '2024-11-01', 'Water and sewer charges for the property', 'Municipal Water Department', 1, 2, NOW(), NOW()),
(13, 2, 6, 1150, '2024-11-01', 'Monthly electricity bill for common areas and units', 'City Power Company', 1, 2, NOW(), NOW()),
(14, 3, 6, 580, '2024-11-01', 'Monthly electricity bill for common areas and units', 'City Power Company', 1, 2, NOW(), NOW()),
-- Property tax (annual)
(15, 1, 11, 38400, '2024-12-01', 'Annual property tax payment', 'City Tax Department', 0, 2, NOW(), NOW()),
(16, 2, 11, 97500, '2024-12-01', 'Annual property tax payment', 'City Tax Department', 0, 2, NOW(), NOW()),
(17, 3, 11, 24200, '2024-12-01', 'Annual property tax payment', 'City Tax Department', 0, 2, NOW(), NOW());

-- Invoices table
INSERT INTO `invoices` (`id`, `tenant_id`, `unit_id`, `lease_id`, `invoice_number`, `amount`, `due_date`, `status`, `description`, `late_fee`, `grace_period_days`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 'INV-0001-122024', 1500, '2024-12-05', 'paid', 'Monthly rent for December 2024', 50, 5, NOW(), NOW()),
(2, 2, 2, 2, 'INV-0002-122024', 2000, '2024-12-05', 'paid', 'Monthly rent for December 2024', 50, 5, NOW(), NOW()),
(3, 3, 25, 11, 'INV-0011-122024', 3000, '2024-12-05', 'paid', 'Monthly rent for December 2024', 100, 5, NOW(), NOW()),
(4, 4, 37, 21, 'INV-0021-122024', 2500, '2024-12-05', 'paid', 'Monthly rent for December 2024', 75, 5, NOW(), NOW()),
(5, 1, 1, 1, 'INV-0001-012025', 1500, '2025-01-05', 'pending', 'Monthly rent for January 2025', 50, 5, NOW(), NOW()),
(6, 2, 2, 2, 'INV-0002-012025', 2000, '2025-01-05', 'pending', 'Monthly rent for January 2025', 50, 5, NOW(), NOW()),
(7, 3, 25, 11, 'INV-0011-012025', 3000, '2025-01-05', 'pending', 'Monthly rent for January 2025', 100, 5, NOW(), NOW()),
(8, 4, 37, 21, 'INV-0021-012025', 2500, '2025-01-05', 'pending', 'Monthly rent for January 2025', 75, 5, NOW(), NOW());

-- Payments table
INSERT INTO `payments` (`id`, `invoice_id`, `tenant_id`, `amount`, `payment_date`, `payment_method_id`, `reference_number`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1500, '2024-12-03', 2, 'PAY-000001', 'completed', 'Payment received on time', NOW(), NOW()),
(2, 2, 2, 2000, '2024-12-04', 3, 'PAY-000002', 'completed', 'Payment received on time', NOW(), NOW()),
(3, 3, 3, 3000, '2024-12-02', 2, 'PAY-000003', 'completed', 'Payment received on time', NOW(), NOW()),
(4, 4, 4, 2500, '2024-12-03', 3, 'PAY-000004', 'completed', 'Payment received on time', NOW(), NOW());

-- Maintenance requests table
INSERT INTO `maintenance_requests` (`id`, `unit_id`, `tenant_id`, `title`, `description`, `priority`, `status`, `requested_date`, `completed_date`, `estimated_cost`, `actual_cost`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Leaky Faucet', 'Tenant reported issue that needs immediate attention.', 'medium', 'completed', '2024-11-15', '2024-11-18', 150, 120, NOW(), NOW()),
(2, 2, 2, 'HVAC Issue', 'Regular maintenance check revealed this problem.', 'high', 'in_progress', '2024-12-01', NULL, 300, NULL, NOW(), NOW()),
(3, 25, 3, 'Electrical Problem', 'Emergency repair required for tenant safety.', 'high', 'pending', '2024-12-05', NULL, 250, NULL, NOW(), NOW()),
(4, 37, 4, 'Lighting Problem', 'Tenant requested this repair service.', 'low', 'completed', '2024-11-20', '2024-11-22', 80, 75, NOW(), NOW());

-- Notifications table
INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 1, 'New Tenant Application', 'A new tenant application has been submitted for review.', 'info', 0, NOW(), NOW()),
(2, 1, 'Maintenance Request', 'A maintenance request has been created and requires attention.', 'warning', 0, NOW(), NOW()),
(3, 2, 'Payment Received', 'Payment has been received for the latest invoice.', 'success', 1, NOW(), NOW()),
(4, 2, 'Lease Expiring Soon', 'A lease is expiring soon and needs renewal or termination.', 'warning', 0, NOW(), NOW()),
(5, 3, 'Property Inspection Due', 'Property inspection is scheduled for this month.', 'info', 0, NOW(), NOW()),
(6, 1, 'Insurance Renewal', 'Insurance policy needs to be renewed.', 'warning', 0, NOW(), NOW()),
(7, 2, 'Tax Payment Due', 'Property tax payment is due this month.', 'error', 0, NOW(), NOW()),
(8, 3, 'New Invoice Generated', 'New invoice has been generated for a tenant.', 'info', 1, NOW(), NOW());

-- Update sequences if using auto-increment
-- Note: This is for MySQL/MariaDB. For other databases, you might need different syntax.
ALTER TABLE `users` AUTO_INCREMENT = 4;
ALTER TABLE `categories` AUTO_INCREMENT = 18;
ALTER TABLE `property_types` AUTO_INCREMENT = 6;
ALTER TABLE `unit_types` AUTO_INCREMENT = 8;
ALTER TABLE `payment_methods` AUTO_INCREMENT = 6;
ALTER TABLE `owners` AUTO_INCREMENT = 4;
ALTER TABLE `properties` AUTO_INCREMENT = 4;
ALTER TABLE `units` AUTO_INCREMENT = 45;
ALTER TABLE `tenants` AUTO_INCREMENT = 5;
ALTER TABLE `leases` AUTO_INCREMENT = 28;
ALTER TABLE `income` AUTO_INCREMENT = 17;
ALTER TABLE `expenses` AUTO_INCREMENT = 18;
ALTER TABLE `invoices` AUTO_INCREMENT = 9;
ALTER TABLE `payments` AUTO_INCREMENT = 5;
ALTER TABLE `maintenance_requests` AUTO_INCREMENT = 5;
ALTER TABLE `notifications` AUTO_INCREMENT = 9;
