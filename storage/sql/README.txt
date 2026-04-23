# Database Setup

## Quick Start

After cloning the project, run the unified setup script:

```bash
php storage/sql/setup.php
```

This will:
- Create the database if it doesn't exist
- Execute schema.sql to create all tables
- Run any pending migrations
- Set up the migrations tracking table

## Alternative Entry Points

You can also use the legacy wrapper:
```bash
php setup_database.php
```

## Verify Setup

To verify the database is set up correctly:
```bash
php storage/sql/debug_database.php
```

## Database Structure

The database includes these tables:
- roles, users, user_profiles
- commodities, commodity_listings, listing_images
- carts, cart_items, orders, order_items, payments, shipments
- conversations, messages, notifications
- price_ticks, favorites
- migrations (tracking table)

## Configuration

Database credentials are configured in the setup scripts:
- Host: 127.0.0.1
- Database: ulimi
- User: root
- Password: (empty)

Update these values in storage/sql/setup.php if your configuration differs.

## Marketplace Database

The marketplace uses a separate database (ulimi_marketplace). Set it up with:
```powershell
php marketplace/setup_database.php
```
