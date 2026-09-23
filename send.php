<?php
// 1. CẤU HÌNH CORS WHITELIST CHO CÁC BÍ DANH
$allowedOrigins = [
    'https://ptithcmfoodexpress.vercel.app',
    'https://ptithcm-food-express.github.io',
    'http://localhost:3000'
];

$origin =$_SERVER['HTTP_ORIGIN'] ?? '';

if (in_array($origin,$allowedOrigins)) {
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
// Tắt hiển thị lỗi HTML để tránh làm hỏng chuỗi JSON trả về
error_reporting(0);
ini_set('display_errors', 0);

// Ép kiểu trả về luôn là JSON
header('Content-Type: application/json; charset=utf-8');

// Khởi chạy session an toàn
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

try {
    // 1. KIỂM TRA WHITELIST DOMAIN
    $allowedDomains = [
        'ptithcmfoodexpress.vercel.app',
        'host120.vietnix.vn',
        'localhost'
    ];

    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';$origin  = isset($_SERVER['HTTP_ORIGIN']) ?$_SERVER['HTTP_ORIGIN'] : '';

    $requestHost = '';
    if (!empty($origin)) {
        $requestHost = parse_url($origin, PHP_URL_HOST);
    } elseif (!empty($referer)) {
        $requestHost = parse_url($referer, PHP_URL_HOST);
    }

    if (!empty($requestHost) && !in_array($requestHost,$allowedDomains)) {
        http_response_code(403);
        echo json_encode([
            'success' => false, 
            'message' => 'Truy cập bị từ chối! Domain không thuộc danh sách cho phép.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 2. RATE LIMIT (15 GIÂY / 1 ĐƠN)
    $currentTime = time();$lastOrderTime = isset($_SESSION['last_order_time']) ?$_SESSION['last_order_time'] : 0;
    if ($currentTime -$lastOrderTime < 15) {
        echo json_encode([
            'success' => false, 
            'message' => 'Thao tác quá nhanh! Vui lòng đợi 15 giây trước khi đặt lại.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 3. ĐỌC DỮ LIỆU INPUT
    $rawInput = file_get_contents('php://input');
    if (empty($rawInput)) {
        echo json_encode(['success' => false, 'message' => 'Dữ liệu rỗng!'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $input = json_decode($rawInput, true);
    if (!$input || !is_array($input) || !isset($input['id'])) {
        echo json_encode(['success' => false, 'message' => 'Dữ liệu đơn hàng không hợp lệ'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $custName     = isset($input['customer']['name']) ? trim($input['customer']['name']) : 'Khách hàng';
    $custPhone    = isset($input['customer']['phone']) ? trim($input['customer']['phone']) : '';$custLocation = isset($input['customer']['location']) ? trim($input['customer']['location']) : 'Sảnh Man Thiện';
    $custNote     = (!empty($input['customer']['note'])) ? trim($input['customer']['note']) : 'Không có';
    
    // BỔ SUNG: Lấy thời gian nhận hàng từ Frontend gửi lên
    $deliveryTime = isset($input['customer']['deliveryTime']) ? trim($input['customer']['deliveryTime']) : 'Giao ngay (Càng sớm càng tốt)';

    $userItems    = (isset($input['items']) && is_array($input['items'])) ? $input['items'] : [];$discount     = isset($input['discount']) ? (int)$input['discount'] : 0;
    $SHIP_FEE     = 5000;

    // 4. VALIDATION
    if (empty($userItems)) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng chọn món ăn!'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if (empty($custPhone)) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng nhập số điện thoại!'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $badWords = ['CODE NGU', 'LÀM MÀU', 'TEST BUG', 'SCRIPT', 'DROP TABLE'];
    foreach ($badWords as$word) {
        if (mb_strpos(mb_strtoupper($custName, 'UTF-8'),$word) !== false || 
            mb_strpos(mb_strtoupper($custNote, 'UTF-8'),$word) !== false) {
            echo json_encode(['success' => false, 'message' => 'Nội dung chứa từ ngữ bị cấm!'], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    // 5. TÍNH GIÁ THẬT TỪ MENU.JSON
    $menuPath = __DIR__ . '/menu.json';$realItemsTotal = 0;
    $validatedItems = [];

    if (file_exists($menuPath)) {$menuData = json_decode(file_get_contents($menuPath), true) ?: [];$itemMap = [];
        
        foreach ($menuData as$res) {
            if (isset($res['items']) && is_array($res['items'])) {
                foreach ($res['items'] as $mItem) {$mTitle = isset($mItem['title']) ?$mItem['title'] : (isset($mItem['name']) ?$mItem['name'] : '');
                    if ($mTitle) {$itemMap[$mTitle] = (int)$mItem['price'];
                    }
                }
            }
        }

        foreach ($userItems as $uItem) {$title = isset($uItem['title']) ?$uItem['title'] : (isset($uItem['name']) ?$uItem['name'] : 'Món ăn');
            $qty   = isset($uItem['qty']) ? max(1, (int)$uItem['qty']) : 1;
            $realPrice = isset($itemMap[$title]) ? $itemMap[$title] : (isset($uItem['price']) ? max(0, (int)$uItem['price']) : 0);
            
            if ($realPrice <= 0) continue;

            $realItemsTotal += ($realPrice * $qty);$validatedItems[] = [
                'title' => $title,
                'qty'   => $qty,
                'price' => $realPrice
            ];
        }
    }

    if ($realItemsTotal <= 0 || empty($validatedItems)) {
        echo json_encode(['success' => false, 'message' => 'Đơn hàng không hợp lệ!'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $finalTotal = max(0,$realItemsTotal + $SHIP_FEE -$discount);
    $input['items'] =$validatedItems;
    $input['total'] =$finalTotal;
    if (!isset($input['status'])) {$input['status'] = 'Chờ xác nhận';
    }

    // 6. LƯU ORDERS.JSON
    $filePath = __DIR__ . '/orders.json';$orders = [];

    if (file_exists($filePath)) {
        $jsonContent = @file_get_contents($filePath);
        if (!empty($jsonContent)) {
            $orders = json_decode($jsonContent, true);
            if (!is_array($orders))$orders = [];
        }
    }

    array_unshift($orders,$input);
    @file_put_contents($filePath, json_encode($orders, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);

    $_SESSION['last_order_time'] = time();

    // 7. DISCORD WEBHOOK
    $DISCORD_WEBHOOK_URL = "https://discord.com/api/webhooks/1551613023821500531/LP7d8qfFQZ-hpIIh-7K0cE6hp7OEo_ZHmOiF7CoIiQJnXFHGRU4Lw8PTCWvgrY8Uk0h-";

    $itemsText = "";
    foreach ($validatedItems as$item) {
        $priceFormatted = number_format($item['price'] * $item['qty'], 0, ',', '.');$itemsText .= "• **{$item['title']}** (x{$item['qty']}): {$priceFormatted}đ\n";
    }

    $payMethod = (isset($input['paymentMethod']) &&$input['paymentMethod'] === 'QR') ? 'Chuyển khoản VietQR' : 'Tiền mặt (COD)';
    $totalFormatted = number_format($finalTotal, 0, ',', '.');

    $embedPayload = [
        "username"   => "PTITHCM Express Bot",
        "avatar_url" => "https://cdn-icons-png.flaticon.com/512/3081/3081559.png",
        "embeds"     => [
            [
                "title"     => "🚀 ĐƠN HÀNG MỚI TỪ PTITHCM EXPRESS!",
                "color"     => 14708808,
                "fields"    => [
                    ["name" => "🆔 Mã đơn", "value" => "`" . $input['id'] . "`", "inline" => true],
                    ["name" => "👤 Khách hàng", "value" => "{$custName} (`{$custPhone}`)", "inline" => true],
                    ["name" => "📍 Điểm hẹn", "value" => $custLocation, "inline" => false],
                    ["name" => "⏰ Giờ nhận hàng", "value" => "**{$deliveryTime}**", "inline" => false], // BỔ SUNG THỜI GIAN NHẬN HÀNG
                    ["name" => "📝 Ghi chú", "value" => $custNote, "inline" => false],
                    ["name" => "🛒 Danh sách món", "value" => $itemsText ?: "Không có món", "inline" => false],
                    ["name" => "💳 Thanh toán", "value" => $payMethod, "inline" => true],
                    ["name" => "💰 TỔNG TIỀN (gồm ship)", "value" => "**{$totalFormatted}đ**", "inline" => true]
                ],
                "footer"    => ["text" => "PTITHCM Express - Trạm Cứu Đói 97 Man Thiện"],
                "timestamp" => date('c')
            ]
        ]
    ];

    $httpCode = 0;
    if (function_exists('curl_init') && !empty($DISCORD_WEBHOOK_URL)) {$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$DISCORD_WEBHOOK_URL);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json; charset=utf-8']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($embedPayload, JSON_UNESCAPED_UNICODE));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    }

    echo json_encode([
        'success'       => true,
        'saved_to_json' => true,
        'discord_sent'  => ($httpCode >= 200 &&$httpCode < 300)
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
exit;