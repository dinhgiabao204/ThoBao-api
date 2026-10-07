<?php

require_once "../config/database.php";
require_once "../utils/response.php";

if ($_SERVER["REQUEST_METHOD"] !== "DELETE") {
    response(
        false,
        "Phương thức không được hỗ trợ",
        null,
        405
    );
}

$id = $_GET["id"] ?? null;

if (!$id) {
    response(
        false,
        "Thiếu id tin tức",
        null,
        400
    );
}

// Kiểm tra tin có tồn tại không
$check = $pdo->prepare(
    "SELECT * FROM news WHERE id = ?"
);

$check->execute([$id]);

$news = $check->fetch(PDO::FETCH_ASSOC);

if (!$news) {
    response(
        false,
        "Không tìm thấy tin tức",
        null,
        404
    );
}

// Xóa tin
$stmt = $pdo->prepare(
    "DELETE FROM news WHERE id = ?"
);

$stmt->execute([$id]);

response(
    true,
    "Xóa tin tức thành công",
    [
        "id" => $id
    ],
    200
);