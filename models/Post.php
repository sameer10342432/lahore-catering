<?php
require_once __DIR__ . '/Database.php';

class Post {
    public static function findBySlug(string $slug): ?array {
        $post = Database::fetch("SELECT * FROM posts WHERE slug = ? AND status IN ('publish', 'published')", [$slug]);
        if ($post) {
            $post['categories'] = self::getCategories($post['id']);
            $post['tags'] = self::getTags($post['id']);
            // Increment view count
            Database::query("UPDATE posts SET views = views + 1 WHERE id = ?", [$post['id']]);
        }
        return $post;
    }

    public static function findById(int $id): ?array {
        $post = Database::fetch("SELECT * FROM posts WHERE id = ?", [$id]);
        if ($post) {
            $post['categories'] = self::getCategories($id);
            $post['tags'] = self::getTags($id);
        }
        return $post;
    }

    public static function paginate(int $page = 1, int $perPage = 9, ?int $categoryId = null, ?int $tagId = null, ?string $search = null): array {
        $offset = ($page - 1) * $perPage;
        $params = [];
        $where = ["p.status IN ('publish', 'published')"];

        $join = "";
        if ($categoryId) {
            $join .= " JOIN post_categories pc ON p.id = pc.post_id";
            $where[] = "pc.category_id = ?";
            $params[] = $categoryId;
        }

        if ($tagId) {
            $join .= " JOIN post_tags pt ON p.id = pt.post_id";
            $where[] = "pt.tag_id = ?";
            $params[] = $tagId;
        }

        if ($search) {
            $where[] = "(p.title LIKE ? OR p.content LIKE ? OR p.excerpt LIKE ?)";
            $term = "%$search%";
            $params[] = $term;
            $params[] = $term;
            $params[] = $term;
        }

        $whereClause = implode(" AND ", $where);

        $countSql = "SELECT COUNT(DISTINCT p.id) as c FROM posts p $join WHERE $whereClause";
        $totalItems = (int)Database::fetch($countSql, $params)['c'];
        $totalPages = ceil($totalItems / $perPage);

        $sql = "SELECT DISTINCT p.* FROM posts p $join WHERE $whereClause ORDER BY p.created_at DESC LIMIT $perPage OFFSET $offset";
        $items = Database::fetchAll($sql, $params);

        foreach ($items as &$item) {
            $item['categories'] = self::getCategories($item['id']);
        }

        return [
            'items' => $items,
            'total' => $totalItems,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => $totalPages
        ];
    }

    public static function getCategories(int $postId): array {
        return Database::fetchAll(
            "SELECT c.* FROM categories c JOIN post_categories pc ON c.id = pc.category_id WHERE pc.post_id = ?",
            [$postId]
        );
    }

    public static function getTags(int $postId): array {
        return Database::fetchAll(
            "SELECT t.* FROM tags t JOIN post_tags pt ON t.id = pt.tag_id WHERE pt.post_id = ?",
            [$postId]
        );
    }

    public static function getRelatedPosts(int $postId, int $limit = 3): array {
        $categories = self::getCategories($postId);
        if (empty($categories)) {
            return Database::fetchAll("SELECT * FROM posts WHERE id != ? AND status IN ('publish', 'published') ORDER BY created_at DESC LIMIT ?", [$postId, $limit]);
        }
        $catId = $categories[0]['id'];
        return Database::fetchAll(
            "SELECT DISTINCT p.* FROM posts p JOIN post_categories pc ON p.id = pc.post_id WHERE pc.category_id = ? AND p.id != ? AND p.status IN ('publish', 'published') ORDER BY p.created_at DESC LIMIT ?",
            [$catId, $postId, $limit]
        );
    }

    public static function getPrevPost(string $createdAt): ?array {
        return Database::fetch(
            "SELECT id, title, slug FROM posts WHERE created_at < ? AND status IN ('publish', 'published') ORDER BY created_at DESC LIMIT 1",
            [$createdAt]
        );
    }

    public static function getNextPost(string $createdAt): ?array {
        return Database::fetch(
            "SELECT id, title, slug FROM posts WHERE created_at > ? AND status IN ('publish', 'published') ORDER BY created_at ASC LIMIT 1",
            [$createdAt]
        );
    }

    public static function count(): int {
        return (int)Database::fetch("SELECT COUNT(*) as c FROM posts")['c'];
    }

    public static function create(array $data): int {
        $now = date('Y-m-d H:i:s');
        Database::query(
            "INSERT INTO posts (title, slug, content, excerpt, author, featured_image, featured_image_alt, featured_image_caption, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['title'],
                $data['slug'],
                $data['content'] ?? '',
                $data['excerpt'] ?? '',
                $data['author'] ?? 'Lahore Catering',
                $data['featured_image'] ?? null,
                $data['featured_image_alt'] ?? null,
                $data['featured_image_caption'] ?? null,
                $data['status'] ?? 'published',
                $now,
                $now
            ]
        );
        $id = (int)Database::lastInsertId();

        if (!empty($data['categories'])) {
            self::setCategories($id, $data['categories']);
        }
        if (!empty($data['tags'])) {
            self::setTags($id, $data['tags']);
        }
        return $id;
    }

    public static function update(int $id, array $data): bool {
        $now = date('Y-m-d H:i:s');
        Database::query(
            "UPDATE posts SET title = ?, slug = ?, content = ?, excerpt = ?, author = ?, featured_image = ?, featured_image_alt = ?, featured_image_caption = ?, status = ?, updated_at = ? WHERE id = ?",
            [
                $data['title'],
                $data['slug'],
                $data['content'] ?? '',
                $data['excerpt'] ?? '',
                $data['author'] ?? 'Lahore Catering',
                $data['featured_image'] ?? null,
                $data['featured_image_alt'] ?? null,
                $data['featured_image_caption'] ?? null,
                $data['status'] ?? 'published',
                $now,
                $id
            ]
        );

        if (isset($data['categories'])) {
            self::setCategories($id, $data['categories']);
        }
        if (isset($data['tags'])) {
            self::setTags($id, $data['tags']);
        }
        return true;
    }

    public static function setCategories(int $postId, array $categoryIds): void {
        Database::query("DELETE FROM post_categories WHERE post_id = ?", [$postId]);
        $stmt = Database::getInstance()->prepare("INSERT INTO post_categories (post_id, category_id) VALUES (?, ?)");
        foreach ($categoryIds as $catId) {
            $stmt->execute([$postId, (int)$catId]);
        }
    }

    public static function setTags(int $postId, array $tagIds): void {
        Database::query("DELETE FROM post_tags WHERE post_id = ?", [$postId]);
        $stmt = Database::getInstance()->prepare("INSERT INTO post_tags (post_id, tag_id) VALUES (?, ?)");
        foreach ($tagIds as $tagId) {
            $stmt->execute([$postId, (int)$tagId]);
        }
    }

    public static function delete(int $id): bool {
        Database::query("DELETE FROM posts WHERE id = ?", [$id]);
        Database::query("DELETE FROM post_categories WHERE post_id = ?", [$id]);
        Database::query("DELETE FROM post_tags WHERE post_id = ?", [$id]);
        Database::query("DELETE FROM seo_metadata WHERE entity_type = 'post' AND entity_id = ?", [$id]);
        return true;
    }
}
