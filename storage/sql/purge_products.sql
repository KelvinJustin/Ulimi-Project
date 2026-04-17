-- SQL script to purge all product-related data and tables
USE ulimi;

-- Drop tables in correct order to respect foreign key constraints
DROP TABLE IF EXISTS price_ticks;
DROP TABLE IF EXISTS listing_images;
DROP TABLE IF EXISTS commodity_listings;
DROP TABLE IF EXISTS commodities;
DROP TABLE IF EXISTS cart_items;
DROP TABLE IF EXISTS carts;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS shipments;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS conversations;

-- Remove commodity insert statements from the end of the original schema
-- The tables are now completely removed
