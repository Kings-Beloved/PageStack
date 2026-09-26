<?php
require_once 'Config.php';

header('Content-Type: application/json');

$uri = $_SERVER['REQUEST_URI'] ?? '';

if (strpos($uri, '/check-schema') !== false) {
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
}

echo json_encode([
    "status" => "success",
    "message" => "PageStack API is live and connected to Aiven database!"
]);