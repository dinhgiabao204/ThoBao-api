<?php

require_once "../config/database.php";
require_once "../utils/response.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    response(
        false,
        "Phương thức không được hỗ trợ",
        null,
        405
    );
}

$data = json_decode(
    file_get_contents("php://input"),
    true
);

if (!$data) {
    response(
        false,
        "Dữ liệu JSON không hợp lệ",
        null,
        400
    );
}

$title = trim($data["title"] ?? "");
$content = trim($data["content"] ?? "");
$image = $data["image"] ?? null;

// TẠM THỜI dùng user 1 để test.
// Sau này sẽ lấy user_id từ token đăng nhập.
$user_id = 1;

if ($title === "" || $content === "") {
    response(
        false,
        "Vui lòng nhập đầy đủ title và content",
        null,
        400
    );
}

$sql = "
    INSERT INTO news (
        title,
        content,
        image,
        user_id
    )
    VALUES (?, ?, ?, ?)
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    $title,
    $content,
    $image,
    $user_id
]);

$id = $pdo->lastInsertId();

response(
    true,
    "Thêm tin tức thành công",
    [
        "id" => $id,
        "title" => $title,
        "content" => $content,
        "image" => $image,
        "user_id" => $user_id
    ],
    201
);