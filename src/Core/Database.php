<?php

namespace App\Core;

use PDO;

use PDOException;

class Database 
{
    private $host;
    private $user;
    private $password;
    private $dbname;
    public $pdo;

    /** Setup Database Connection */
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
        ];

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->password, $options);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

}

