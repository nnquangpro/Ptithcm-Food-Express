<?php
// 1. CẤU HÌNH CORS WHITELIST CHO CÁC BÍ DANH
$allowedOrigins = [
    'https://ptithcmfoodexpress.vercel.app',
    'https://ptithcm-food-express.github.io',
    'http://localhost:3000'
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

if (in_array($origin, $allowedOrigins)) {
    header("Access-Control-Allow-Origin: " . $origin);
    header("Access-Control-Allow-Credentials: true");
}

header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// 2. XỬ LÝ PREFLIGHT REQUEST (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
date_default_timezone_set('Asia/Ho_Chi_Minh');
header('Content-Type: application/json; charset=utf-8');

$jsonFile = 'rate.json';

// Nếu nhận request POST -> Ghi đánh giá mới
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || !isset($input['restaurant_id']) || !isset($input['comment'])) {
        echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ!'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // -------------------------------------------------------------
    // 🛡️ CHẶN BUG XSS & CHUẨN HÓA DỮ LIỆU INPUT
    // -------------------------------------------------------------
    // 1. Loại bỏ tất cả thẻ HTML/Script
    $rawName    = strip_tags($input['name'] ?? '');
    $rawComment = strip_tags($input['comment'] ?? '');

    // 2. Chuyển đổi các ký tự đặc biệt thành HTML Entities (Anti-XSS Strict)
    $cleanName    = htmlspecialchars($rawName, ENT_QUOTES, 'UTF-8');
    $cleanComment = htmlspecialchars($rawComment, ENT_QUOTES, 'UTF-8');

    // 3. Đặt giá trị mặc định nếu rỗng
    $name    = !empty(trim($cleanName)) ? trim($cleanName) : 'Sinh viên PTIT';
    $comment = trim($cleanComment);
    $stars   = isset($input['stars']) ? intval($input['stars']) : 5;
    $resId   = trim($input['restaurant_id']);

    // Bắt buộc bình luận phải có ít nhất 10 ký tự sau khi lọc
    if (mb_strlen($comment, 'UTF-8') < 10) {
        echo json_encode(['success' => false, 'message' => 'Bình luận phải chứa ít nhất 10 ký tự hợp lệ!'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $allRates = [];
    if (file_exists($jsonFile)) {
        $allRates = json_decode(file_get_contents($jsonFile), true) ?: [];
    }

    if (!isset($allRates[$resId])) {
        $allRates[$resId] = [];
    }

    // Thêm đánh giá mới đã lọc sạch XSS lên đầu danh sách
    array_unshift($allRates[$resId], [
        'name'    => $name,
        'stars'   => $stars,
        'date'    => date('Y-m-d'),
        'comment' => $comment
    ]);

    // Lưu lại file rate.json
    file_put_contents($jsonFile, json_encode($allRates, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);

    echo json_encode(['success' => true, 'message' => 'Lưu đánh giá thành công!'], JSON_UNESCAPED_UNICODE);
    exit;
}

// Nếu nhận request GET -> Lấy danh sách đánh giá
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $resId = $_GET['id'] ?? '';
    if (file_exists($jsonFile)) {
        $allRates = json_decode(file_get_contents($jsonFile), true) ?: [];
        $reviews  = $allRates[$resId] ?? [];
        echo json_encode(['success' => true, 'data' => $reviews], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(['success' => true, 'data' => []], JSON_UNESCAPED_UNICODE);
    }
    exit;
}
?>