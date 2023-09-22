<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * Database class for handling database connections.
 */
class Database 
{
    /** @var string|null The database host. */
    private $host;
    
    /** @var string The database user. */
    private $user;
    
    /** @var string The database password. */
    private $password;
    
    /** @var string The database name. */
    private $dbname;
    
    /** @var PDO The PDO database connection object. */
    public $pdo;

    /**
     * Constructor to set up the database connection.
     */
    public function __construct()
    {
        $this->host = $_ENV['HOST'];
        $this->user = $_ENV['USER'];
        $this->password = $_ENV['PASSWORD'];
        $this->dbname = $_ENV['DB_NAME'];
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->password, $options);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}
