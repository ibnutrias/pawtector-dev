-- Add total_price column to appointments table
ALTER TABLE appointments ADD COLUMN total_price DECIMAL(15, 2) DEFAULT 0 AFTER status;
