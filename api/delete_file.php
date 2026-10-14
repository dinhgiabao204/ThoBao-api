<?php

require_once "../utils/response.php";

if ($_SERVER["REQUEST_METHOD"] !== "DELETE") {
    response(
        false,
        "Phương thức không được hỗ trợ",
        null,
        405
    );
}

$filename = $_GET["filename"] ?? "";

if ($filename === "") {
    response(
        false,
        "Thiếu tên file",
        null,
        400
    );
}

// Chỉ lấy tên file, tránh kiểu ../../file
$filename = basename($filename);

$filePath = __DIR__ . "/../uploads/" . $filename;

if (!file_exists($filePath)) {
    response(
        false,
        "File không tồn tại",
        null,
        404
    );
}

if (!unlink($filePath)) {
    response(
        false,
        "Không thể xóa file",
        null,
        500
    );
}

response(
    true,
    "Xóa file thành công",
    [
        "filename" => $filename
    ],
    200
);