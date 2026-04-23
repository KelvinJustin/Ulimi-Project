<?php
/**
 * Database Setup Script (Legacy Entry Point)
 * 
 * This script is a convenience wrapper for the new unified setup system.
 * It simply calls storage/sql/setup.php which handles all database initialization.
 * 
 * Usage: php setup_database.php
 * 
 * @deprecated Use php storage/sql/setup.php directly instead
 */

// Call the unified setup script
require_once __DIR__ . '/storage/sql/setup.php';
