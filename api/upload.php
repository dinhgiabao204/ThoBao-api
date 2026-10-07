<?php

require_once "../utils/response.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    response(
        false,
        "Phương thức không được hỗ trợ",
        null,
        405
    );
}

if (!isset($_FILES["file"])) {
    response(
        false,
        "Vui lòng chọn file",
        null,
        400
    );
}

$file = $_FILES["file"];

if ($file["error"] !== UPLOAD_ERR_OK) {
    response(
        false,
        "Upload file thất bại",
        null,
        400
    );
}

// Giới hạn 5MB
if ($file["size"] > 5 * 1024 * 1024) {
    response(
        false,
        "File không được lớn hơn 5MB",
        null,
        400
    );
}

$extension = strtolower(
    pathinfo(
        $file["name"],
        PATHINFO_EXTENSION
    )
);

$allowedExtensions = [
    "jpg",
    "jpeg",
    "png",
    "gif"
];

if (!in_array($extension, $allowedExtensions, true)) {
    response(
        false,
        "Chỉ cho phép jpg, jpeg, png, gif",
        null,
        400
    );
}

$uploadDir = __DIR__ . "/../uploads/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$filename =
    uniqid("news_", true)
    . "."
    . $extension;

$destination =
    $uploadDir . $filename;

if (!move_uploaded_file(
    $file["tmp_name"],
    $destination
)) {
    response(
        false,
        "Không thể lưu file",
        null,
        500
    );
}

response(
    true,
    "Upload file thành công",
    [
        "filename" => $filename,
        "path" => "uploads/" . $filename,
              "url" => "http://localhost/ThoBao-api/uploads/" . $filename
    ],
    201
);