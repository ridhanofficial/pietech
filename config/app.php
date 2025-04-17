<?php
/**
 * Application Configuration File
 * 
 * This file contains the main configuration for the PIETECH Events Platform.
 */

// Application settings
define('APP_NAME', 'PIETECH Events Platform');
define('APP_URL', 'http://localhost/pietech-events');
define('APP_ENV', 'development'); // 'development' or 'production'
define('APP_DEBUG', true);

// Session configuration
define('SESSION_LIFETIME', 120); // minutes
define('SESSION_SECURE', false); // Set to true in production with HTTPS

// Security settings
define('APP_KEY', 'your-secure-app-key'); // Change this to a random string in production
define('CSRF_TOKEN_NAME', 'csrf_token');

// Timezone and locale settings
date_default_timezone_set('UTC');
define('APP_LOCALE', 'en_US'); 