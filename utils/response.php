<?php

header("Content-Type: application/json; charset=UTF-8");

function response($success, $message, $data = null, $status = 200)
{
    http_response_code($status);

    echo json_encode([
    "success" => $success,
    "message" => $message,
    "data" => $data
], JSON_UNESCAPED_UNICODE);

    exit;
}