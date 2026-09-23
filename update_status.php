<?php
header('Content-Type: application/json; charset=utf-8');

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (isset($data['orderId']) && isset($data['status'])) {
    $filePath = 'orders.json';
    
    if (file_exists($filePath)) {
        $orders = json_decode(file_get_contents($filePath), true) ?? [];
        
        // Tìm và cập nhật trạng thái của đơn hàng theo orderId
        foreach ($orders as &$order) {
            if ($order['id'] === $data['orderId']) {
                $order['status'] = $data['status'];
                break;
            }
        }
        
        file_put_contents($filePath, json_encode($orders, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo json_encode(['status' => 'success', 'message' => 'Đã cập nhật trạng thái đơn hàng']);
        exit;
    }
}

echo json_encode(['status' => 'error', 'message' => 'Cập nhật thất bại']);
?>