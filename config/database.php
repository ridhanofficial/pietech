<?php
/**
 * Database Configuration File
 * 
 * This file contains the database connection parameters for the PIETECH Events Platform.
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'pietech_events');
define('DB_USER', 'root');
define('DB_PASS', '');

// Attempt to establish a database connection
try {
    $db = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
} 