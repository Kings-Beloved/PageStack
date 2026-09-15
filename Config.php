<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// Handle OPTIONS preflight requests immediately
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

class Config {
    // Read Railway environment variables dynamically, falling back to local defaults
    private $host;
    private $username;
    private $database;
    private $password;
    private $port;

    protected $connection;

    public function __construct()
    {
        $this->host     = getenv('MYSQLHOST') ?: 'localhost';
        $this->username = getenv('MYSQLUSER') ?: 'root';
        $this->password = getenv('MYSQLPASSWORD') ?: '';
        $this->database = getenv('MYSQLDATABASE') ?: 'book_store';
        $this->port     = getenv('MYSQLPORT') ?: 3306;

        try {
            $this->connection = mysqli_connect(
                $this->host,
                $this->username,
                $this->password,
                $this->database,
                (int)$this->port
            );
        } catch(mysqli_sql_exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
            exit();
        }
    }
}

// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
// header("Access-Control-Allow-Headers: Content-Type, Authorization");
// header("Content-Type: application/json");

// class Config {
// private $host = 'localhost';
// private $username = 'root';
// private $database = 'book_store';
// private $password = '';

// protected $connection;

// public function __construct()
// {
//     try{
//         $this->connection = mysqli_connect($this->host, $this->username, $this->password, $this->database,);

//     } catch(mysqli_sql_exception $e){
//         echo 'connection failed because'. $e->getMessage();
//     }

//     }
// }

// $newConfig = new Config();
