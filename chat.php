<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$jsonFile = 'chat.json';

// 1. LẤY DANH SÁCH TIN NHẮN (GET)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (file_exists($jsonFile)) {
        echo file_get_contents($jsonFile);
    } else {
        echo json_encode([]);
    }
    exit;
}

// 2. GỬI TIN NHẮN (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rawInput = file_get_contents('php://input');
    $data = json_decode($rawInput, true);

    if (!$data || empty($data['text'])) {
        echo json_encode(['success' => false, 'message' => 'Nội dung tin nhắn không được để trống!']);
        exit;
    }

    // KIỂM TRA XEM CÓ PHẢI ADMIN ĐANG LOGGED IN KHÔNG
    $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;

    if ($isAdmin) {
        $sender   = 'nquang';
        $senderId = 'admin_root';
        $role     = 'admin';
    } else {
        $sender   = !empty($data['sender']) ? htmlspecialchars(trim($data['sender']), ENT_QUOTES, 'UTF-8') : 'Sinh Viên Anonymous';
        $senderId = !empty($data['sender_id']) ? htmlspecialchars(trim($data['sender_id']), ENT_QUOTES, 'UTF-8') : 'anon_user';
        $role     = 'user';
    }

    $text = htmlspecialchars(trim($data['text']), ENT_QUOTES, 'UTF-8');
    $time = date('H:i d/m');

    $messages = [];
    if (file_exists($jsonFile)) {
        $messages = json_decode(file_get_contents($jsonFile), true) ?: [];
    }

    $newMessage = [
        'id'        => uniqid('msg_'),
        'sender'    => $sender,
        'sender_id' => $senderId,
        'role'      => $role,
        'text'      => $text,
        'time'      => $time
    ];

    $messages[] = $newMessage;

    if (count($messages) > 100) {
        $messages = array_slice($messages, -100);
    }

    if (file_put_contents($jsonFile, json_encode($messages, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT))) {
        echo json_encode(['success' => true, 'data' => $newMessage]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi lưu tin nhắn!']);
    }
    exit;
}
?>