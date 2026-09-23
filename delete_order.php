<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
header('Content-Type: application/json; charset=utf-8');

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (isset($data['orderId'])) {
    $filePath = __DIR__ . '/orders.json';
    
    if (file_exists($filePath)) {
        $orders = json_decode(file_get_contents($filePath), true) ?? [];
        
        // Lọc bỏ đơn hàng cần xóa
        $filteredOrders = array_values(array_filter($orders, function($order) use ($data) {
            return $order['id'] !== $data['orderId'];
        }));
        
        file_put_contents($filePath, json_encode($filteredOrders, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo json_encode(['status' => 'success', 'message' => 'Đã xóa đơn hàng']);
        exit;
    }
}

echo json_encode(['status' => 'error', 'message' => 'Xóa thất bại']);
?>