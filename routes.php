<?php
require 'Router.php';
require_once 'Auth.php';

$router = new Router();
$auth = new Auth();

$router->add('POST', '/auth/createUser', [$auth, 'create_user']);
$router->add('POST', '/auth/loginUser', [$auth, 'login_user']);

$router->add('GET', '/check-schema', function() {
    $config = new Config();
    $result = mysqli_query($config->connection, "DESCRIBE user");

    if (!$result) {
        echo json_encode(["error" => mysqli_error($config->connection)]);
        exit();
    }

    $columns = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $columns[] = $row['Field'];
    }

    echo json_encode(["user_columns" => $columns]);
    exit();

    
});

$router->add('GET', '/update-schema', function() {
    $config = new Config();
    
    // Add missing columns to match your exact Auth.php naming
    $sql = "ALTER TABLE user 
            ADD COLUMN First_name VARCHAR(100),
            ADD COLUMN Last_name VARCHAR(100),
            ADD COLUMN Password VARCHAR(255),
            ADD COLUMN Country VARCHAR(100),
            ADD COLUMN Occupation VARCHAR(100)";
            
    if (mysqli_query($config->connection, $sql)) {
        echo json_encode(["status" => 200, "message" => "Database schema updated successfully!"]);
    } else {
        echo json_encode(["status" => 500, "error" => mysqli_error($config->connection)]);
    }
    exit();
});

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace('/PageStack', '', $path);

$router->dispatch($method, $path);