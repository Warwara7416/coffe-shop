<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json'); // Устанавливаем заголовок JSON

$data = json_decode(file_get_contents('php://input'), true);

if (!empty($data)) {
    $_SESSION['cart'] = array_map(function($item) {
        return [
            'id' => isset($item['id']) ? $item['id'] : null,
            'name' => isset($item['name']) ? $item['name'] : '',
            'price' => isset($item['price']) ? $item['price'] : 0,
            'quantity' => isset($item['quantity']) ? $item['quantity'] : 0
        ];
    }, $data);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
