<?php

require_once "../config/database.php";
require_once "../utils/response.php";
require_once "../models/News.php";

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
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

$newsModel = new News($pdo);

$news = $newsModel->findById($id);

if (!$news) {
    response(
        false,
        "Không tìm thấy tin tức",
        null,
        404
    );
}

response(
    true,
    "Lấy chi tiết tin tức thành công",
    $news,
    200
);