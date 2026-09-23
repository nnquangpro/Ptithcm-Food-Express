<?php
session_start();

// 1. CẤU HÌNH BẢO MẬT BẰNG MD5 + SALT
$SALT = "PTITHCM_EXPRESS_SECRET_KEY_2026@#$";

// Mật khẩu hiện tại: "123@_" + $SALT
$ADMIN_PASSWORD_HASH = md5("admin" . $SALT); 

$error = "";

if (isset($_POST['password'])) {
    $inputPassword = $_POST['password'];
    
    // Băm mật khẩu người dùng nhập vào kèm chuỗi Salt để so sánh
    $userHash = md5($inputPassword . $SALT);

    if ($userHash === $ADMIN_PASSWORD_HASH) {
        // Lưu Session xác thực
        $_SESSION['is_admin'] = true;
        // BỔ SUNG: Lưu User-Agent để khớp với kiểm tra bên admin.php
        $_SESSION['admin_ua'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        // Chuyển hướng sang /admin (hoặc admin.php)
        header("Location: admin");
        exit;
    } else {
        $error = "Mật khẩu không chính xác!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi" class="bg-slate-950">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng Nhập Admin - PTITHCM Food Express</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
  <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center p-4">

  <div class="bg-slate-900 border border-slate-800 p-8 rounded-3xl max-w-md w-full space-y-6 shadow-2xl">
    <div class="text-center space-y-2">
      <div class="w-12 h-12 rounded-2xl bg-rose-600 mx-auto flex items-center justify-center font-black text-white text-2xl shadow-lg shadow-rose-600/40">
        P
      </div>
      <h1 class="text-2xl font-black text-white">Xác Minh Quyền Admin</h1>
      <p class="text-xs text-slate-400">Nhập mật khẩu quản trị để tiếp tục</p>
    </div>

    <?php if ($error): ?>
      <div class="bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs p-3 rounded-xl text-center font-bold">
        <?= $error ?>
      </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
      <div>
        <label class="text-[11px] font-bold text-slate-400 uppercase block mb-1">Mật Khẩu *</label>
        <div class="relative">
          <input type="password" name="password" required placeholder="••••••••" class="w-full bg-slate-950 border border-slate-800 text-white rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-rose-500 focus:outline-none">
        </div>
      </div>

      <button type="submit" class="w-full bg-rose-600 hover:bg-rose-500 text-white font-extrabold text-sm py-3 rounded-xl transition shadow-lg shadow-rose-600/30">
        Đăng Nhập Dashboard 🚀
      </button>
    </form>

    <div class="text-center pt-2">
      <a href="index.php" class="text-xs text-slate-500 hover:text-slate-400">← Quay lại trang khách</a>
    </div>
  </div>

</body>
</html>