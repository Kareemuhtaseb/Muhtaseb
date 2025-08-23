-- SQLite Database Optimization Script

-- Enable WAL mode for better concurrency
PRAGMA journal_mode = WAL;

-- Optimize synchronous mode for better performance
PRAGMA synchronous = NORMAL;

-- Increase cache size for better performance
PRAGMA cache_size = 10000;

-- Use memory for temporary storage
PRAGMA temp_store = MEMORY;

-- Enable memory-mapped I/O
PRAGMA mmap_size = 268435456;

-- Optimize page size
PRAGMA page_size = 4096;

-- Enable foreign key constraints
PRAGMA foreign_keys = ON;

-- Analyze tables for better query planning
ANALYZE;

-- Create indexes for better query performance
CREATE INDEX IF NOT EXISTS idx_units_unit_number ON units(unit_number);
CREATE INDEX IF NOT EXISTS idx_units_status ON units(status);
CREATE INDEX IF NOT EXISTS idx_tenants_email ON tenants(email);
CREATE INDEX IF NOT EXISTS idx_tenants_unit_id ON tenants(unit_id);
CREATE INDEX IF NOT EXISTS idx_payments_tenant_id ON payments(tenant_id);
CREATE INDEX IF NOT EXISTS idx_payments_payment_date ON payments(payment_date);
CREATE INDEX IF NOT EXISTS idx_expenses_date ON expenses(date);

-- Create composite indexes for common queries
CREATE INDEX IF NOT EXISTS idx_units_status_rent ON units(status, monthly_rent);
CREATE INDEX IF NOT EXISTS idx_tenants_unit_start ON tenants(unit_id, start_date);

-- Vacuum database to optimize storage
VACUUM;
