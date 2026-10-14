<?php

require_once "../config/database.php";
require_once "../utils/response.php";

if ($_SERVER["REQUEST_METHOD"] !== "PUT") {
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

// Nếu không gửi trường nào thì giữ dữ liệu cũ
$title = trim(
    $data["title"] ?? $news["title"]
);

$content = trim(
    $data["content"] ?? $news["content"]
);

$image = array_key_exists("image", $data)
    ? $data["image"]
    : $news["image"];

if ($title === "" || $content === "") {
    response(
        false,
        "Title và content không được để trống",
        null,
        400
    );
}

$sql = "
    UPDATE news
    SET
        title = ?,
        content = ?,
        image = ?
    WHERE id = ?
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    $title,
    $content,
    $image,
    $id
]);

// Lấy lại dữ liệu sau khi sửa
$stmt = $pdo->prepare(
    "SELECT * FROM news WHERE id = ?"
);

$stmt->execute([$id]);

$updatedNews =
    $stmt->fetch(PDO::FETCH_ASSOC);

response(
    true,
    "Cập nhật tin tức thành công",
    $updatedNews,
    200
);