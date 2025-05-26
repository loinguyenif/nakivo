<?php
require_once 'callArticle.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$call = new callArticle();

if ($uri[0] !== 'articles') {
    http_response_code(404);
    echo json_encode(['error' => 'Not Found']);
    exit;
}

$id = isset($uri[1]) ? (int) $uri[1] : null;

switch ($method) {
    case 'GET':
        if ($id) {
            $article = $call->get($id);
            if ($article) {
                echo json_encode($article);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Article not found']);
            }
        } else {
            echo json_encode($call->getAll());
        }
        break;

    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true);
        $result = $call->create($input);
        if ($result['success']) {
            http_response_code(201);
            echo json_encode($result['article']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => $result['error']]);
        }
        break;

    case 'PUT':
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing ID']);
            exit;
        }
        $input = json_decode(file_get_contents('php://input'), true);
        $result = $call->update($id, $input);
        if ($result['success']) {
            echo json_encode($result['article']);
        } else {
            http_response_code($result['code']);
            echo json_encode(['error' => $result['error']]);
        }
        break;

    case 'DELETE':
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing ID']);
            exit;
        }
        $deleted = $call->delete($id);
        if ($deleted) {
            echo json_encode(['message' => 'Deleted']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Article not found']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}
