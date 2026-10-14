<?php

require_once "../config/database.php";
require_once "../utils/response.php";

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    response(
        false,
        "Phương thức không được hỗ trợ",
        null,
        405
    );
}

$search = trim($_GET["search"] ?? "");

$sort = strtoupper($_GET["sort"] ?? "DESC");
if (!in_array($sort, ["ASC", "DESC"], true)) {
    $sort = "DESC";
}

$sql = "
    SELECT
        news.id,
        news.title,
        news.content,
        news.image,
        news.user_id,
        news.created_at,
        news.updated_at,
        users.name AS author_name
    FROM news
    LEFT JOIN users
        ON news.user_id = users.id
    WHERE news.title LIKE ?
       OR news.content LIKE ?
    ORDER BY news.created_at $sort
";

$stmt = $pdo->prepare($sql);

$keyword = "%" . $search . "%";

$stmt->execute([
    $keyword,
    $keyword
]);

$news = $stmt->fetchAll(PDO::FETCH_ASSOC);

response(
    true,
    "Lấy danh sách tin tức thành công",
    $news,
    200
);