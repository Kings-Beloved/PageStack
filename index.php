<?php
header('Content-Type: application/json');
echo json_encode([
    "status" => "success",
    "message" => "PageStack API is live and connected to Aiven database!"
]);
