<?php

require_once "../config/database.php";
require_once "../utils/response.php";

$data = json_decode(
    file_get_contents("php://input"),
    true
);

$name = $data["name"] ?? "";
$email = $data["email"] ?? "";
$password = $data["password"] ?? "";

if (!$name || !$email || !$password) {
    response(
        false,
        "Vui lòng nhập đầy đủ thông tin",
        null,
        400
    );
}

$check = $pdo->prepare(
    "SELECT id FROM users WHERE email = ?"
);

$check->execute([$email]);

if ($check->fetch()) {
    response(
        false,
        "Email đã tồn tại",
        null,
        409
    );
}

$hashedPassword = password_hash(
    $password,
    PASSWORD_DEFAULT
);

$sql = "INSERT INTO users
        (name, email, password)
        VALUES (?, ?, ?)";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    $name,
    $email,
    $hashedPassword
]);

response(
    true,
    "Đăng ký thành công",
    null,
    201
);