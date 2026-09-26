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
    private $host;
    private $username;
    private $database;
    private $password;
    private $port;

    protected $connection;

    public function __construct()
    {
        $this->host     = getenv('MYSQLHOST')     ?: ($_ENV['MYSQLHOST']     ?? 'localhost');
        $this->username = getenv('MYSQLUSER')     ?: ($_ENV['MYSQLUSER']     ?? 'root');
        $this->password = getenv('MYSQLPASSWORD') ?: ($_ENV['MYSQLPASSWORD'] ?? '');
        $this->database = getenv('MYSQLDATABASE') ?: ($_ENV['MYSQLDATABASE'] ?? 'book_store');
        $this->port     = getenv('MYSQLPORT')     ?: ($_ENV['MYSQLPORT']     ?? 3306);

        try {
            $this->connection = mysqli_init();

            if ($this->host !== 'localhost' && $this->host !== '127.0.0.1') {
                mysqli_ssl_set($this->connection, NULL, NULL, NULL, NULL, NULL);
                mysqli_real_connect(
                    $this->connection,
                    $this->host,
                    $this->username,
                    $this->password,
                    $this->database,
                    (int)$this->port,
                    MYSQLI_CLIENT_SSL
                );
            } else {
                mysqli_real_connect(
                    $this->connection,
                    $this->host,
                    $this->username,
                    $this->password,
                    $this->database,
                    (int)$this->port
                );
            }
        } catch(mysqli_sql_exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
            exit();
        }
    }
}