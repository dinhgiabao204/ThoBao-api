<?php

class News
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Danh sách + tìm kiếm + sắp xếp
    public function getAll($search = "", $sort = "DESC")
    {
        $sort = strtoupper($sort);

        if (!in_array($sort, ["ASC", "DESC"], true)) {
            $sort = "DESC";
        }

        $sql = "
            SELECT
                news.*,
                users.name AS author_name
            FROM news
            LEFT JOIN users
                ON news.user_id = users.id
            WHERE news.title LIKE ?
               OR news.content LIKE ?
            ORDER BY news.created_at $sort
        ";

        $stmt = $this->pdo->prepare($sql);

        $keyword = "%" . $search . "%";

        $stmt->execute([
            $keyword,
            $keyword
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết 1 tin
    public function findById($id)
    {
        $sql = "
            SELECT
                news.*,
                users.name AS author_name
            FROM news
            LEFT JOIN users
                ON news.user_id = users.id
            WHERE news.id = ?
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm tin
    public function create(
        $title,
        $content,
        $image,
        $userId
    ) {
        $sql = "
            INSERT INTO news
                (title, content, image, user_id)
            VALUES
                (?, ?, ?, ?)
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $title,
            $content,
            $image,
            $userId
        ]);

        return $this->pdo->lastInsertId();
    }

    // Sửa tin
    public function update(
        $id,
        $title,
        $content,
        $image
    ) {
        $sql = "
            UPDATE news
            SET title = ?,
                content = ?,
                image = ?
            WHERE id = ?
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            $title,
            $content,
            $image,
            $id
        ]);
    }

    // Xóa tin
    public function delete($id)
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM news WHERE id = ?"
        );

        return $stmt->execute([$id]);
    }
}