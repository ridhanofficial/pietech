<?php
/**
 * Database Connection Class
 * 
 * This class provides a PDO database connection for the application.
 */

class Database {
    private $host;
    private $dbName;
    private $username;
    private $password;
    private $conn;
    
    public function __construct() {
        // Read database configuration from environment variables
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->dbName = getenv('DB_NAME') ?: 'pietech_events';
        $this->username = getenv('DB_USER') ?: 'root';
        $this->password = getenv('DB_PASS') ?: '';
        
        $this->connect();
    }
    
    /**
     * Connect to the database
     * 
     * @return void
     */
    private function connect() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];
            
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            // In production, you might want to log this error rather than displaying it
            if (getenv('APP_ENV') === 'production') {
                error_log("Database connection failed: " . $e->getMessage());
                die("A database error occurred. Please try again later.");
            } else {
                // In development, show the error
                die("Database connection failed: " . $e->getMessage());
            }
        }
    }
    
    /**
     * Get the database connection
     * 
     * @return PDO The database connection
     */
    public function getConnection() {
        return $this->conn;
    }
} 