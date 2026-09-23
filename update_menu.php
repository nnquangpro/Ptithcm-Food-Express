<?php
header('Content-Type: application/json; charset=utf-8');

$input = file_get_contents('php://input');
$newMenu = json_decode($input, true);

if (is_array($newMenu)) {
    $filePath = __DIR__ . '/menu.json';
    
    if (file_put_contents($filePath, json_encode($newMenu, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false) {
        echo json_encode(['status' => 'success', 'message' => 'Đã cập nhật file menu.json']);
        exit;
    }
}

echo json_encode(['status' => 'error', 'message' => 'Cập nhật menu thất bại']);
?>