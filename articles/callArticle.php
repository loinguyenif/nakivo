<?php

class callArticle {
    private static $articles = [];
    private static $nextId = 1;

    public function getAll() {
        return array_values(self::$articles);
    }

    public function get($id) {
        return self::$articles[$id] ?? null;
    }

    public function create($data) {
        $validation = $this->validate($data, true);
        if (!$validation['valid']) {
            return ['success' => false, 'error' => $validation['error']];
        }

        $article = [
            'id' => self::$nextId++,
            'title' => $data['title'],
            'content' => $data['content'],
            'author' => $data['author'],
            'created_at' => date('c')
        ];

        self::$articles[$article['id']] = $article;

        return ['success' => true, 'article' => $article];
    }

    public function update($id, $data) {
        if (!isset(self::$articles[$id])) {
            return ['success' => false, 'code' => 404, 'error' => 'Article not found'];
        }

        $validation = $this->validate($data, false);
        if (!$validation['valid']) {
            return ['success' => false, 'code' => 400, 'error' => $validation['error']];
        }

        $article = &self::$articles[$id];
        $article['title'] = $data['title'] ?? $article['title'];
        $article['content'] = $data['content'] ?? $article['content'];
        $article['author'] = $data['author'] ?? $article['author'];

        return ['success' => true, 'article' => $article];
    }

    public function delete($id) {
        if (!isset(self::$articles[$id])) {
            return false;
        }
        unset(self::$articles[$id]);
        return true;
    }

    private function validate($data, $strict = true) {
        $required = ['title', 'content', 'author'];
        foreach ($required as $field) {
            if ($strict && (!isset($data[$field]) || !is_string($data[$field]))) {
                return ['valid' => false, 'error' => "Missing or invalid '$field'"];
            }
            if (!$strict && isset($data[$field]) && !is_string($data[$field])) {
                return ['valid' => false, 'error' => "Invalid type for '$field'"];
            }
        }
        return ['valid' => true];
    }
}
