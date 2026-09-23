<?php
session_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
// Xử lý đăng xuất
if (isset($_GET['action']) &&$_GET['action'] === 'logout') {
    unset($_SESSION['is_admin']);
    unset($_SESSION['admin_ua']);
    session_destroy();
    header("Location: login.php");
    exit;
}

// Bắt buộc đăng nhập & Kiểm tra User-Agent trùng khớp (Anti Session Hijacking)
$currentUA =$_SERVER['HTTP_USER_AGENT'] ?? '';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true || 
    !isset($_SESSION['admin_ua']) || $_SESSION['admin_ua'] !== $currentUA) {
    // Nếu phát hiện Session bất thường hoặc đổi trình duyệt -> Xóa sạch & đá về Login
    unset($_SESSION['is_admin']);
    unset($_SESSION['admin_ua']);
    session_destroy();
    
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi" class="bg-slate-950">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - PTITHCM Food Express</title>
  
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
  
  <link href="//cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.js"></script>

  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
  </style>
</head>
<body class="bg-slate-950 text-slate-100 antialiased min-h-screen flex flex-col justify-between">

  <div>
    <!-- Header Admin Navigation -->
    <header class="sticky top-3 z-40 px-4 max-w-7xl mx-auto">
      <div class="bg-slate-900/90 backdrop-blur-md text-white rounded-2xl px-6 py-3.5 shadow-2xl border border-slate-800 flex items-center justify-between">
        <div class="flex items-center gap-3 cursor-pointer" onclick="window.location.href='index.php'">
          <div class="w-10 h-10 rounded-xl bg-rose-600 flex items-center justify-center font-black text-white text-xl shadow-lg shadow-rose-600/40">
            P
          </div>
          <div>
            <div class="flex items-center gap-2">
              <span class="font-extrabold text-lg tracking-tight leading-none">PTIT <span class="text-rose-500">ADMIN</span></span>
              <span class="bg-rose-500/20 text-rose-400 text-[10px] font-bold px-2 py-0.5 rounded-md border border-rose-500/30">Server Live</span>
            </div>
            <span class="text-xs text-slate-400 font-medium">Hệ Thống Quản Lý Đơn Hàng từ Server</span>
          </div>
        </div>

        <div class="flex items-center gap-3">
          <a href="index.php" class="bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs sm:text-sm px-4 py-2.5 rounded-xl border border-slate-700 flex items-center gap-2 transition">
            <i class="fa-solid fa-store text-rose-500"></i>
            <span class="hidden sm:inline">Xem Trang Khách</span>
          </a>
          <a href="admin.php?action=logout" class="bg-rose-600/20 hover:bg-rose-600/30 text-rose-400 font-bold text-xs sm:text-sm px-3.5 py-2.5 rounded-xl border border-rose-500/30 transition flex items-center gap-1.5">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span class="hidden sm:inline">Đăng xuất</span>
          </a>
        </div>
      </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 pt-6 space-y-8">
      <!-- Tabs Navigation -->
      <div class="flex items-center gap-3 border-b border-slate-800 pb-4">
        <button onclick="switchTab('orders')" id="tab-btn-orders" class="bg-rose-600 text-white font-extrabold text-xs sm:text-sm px-5 py-2.5 rounded-xl shadow-lg shadow-rose-600/20 transition flex items-center gap-2">
          <i class="fa-solid fa-receipt"></i>
          <span>Quản Lý Đơn Hàng</span>
          <span id="pending-count-badge" class="bg-white text-rose-600 text-[10px] px-2 py-0.5 rounded-full font-black">0</span>
        </button>

        <button onclick="switchTab('stores')" id="tab-btn-stores" class="bg-slate-900 hover:bg-slate-800 text-slate-400 font-bold text-xs sm:text-sm px-5 py-2.5 rounded-xl border border-slate-800 transition flex items-center gap-2">
          <i class="fa-solid fa-utensils"></i>
          <span>Quản Lý Quán & Món Ăn</span>
        </button>
      </div>

      <!-- TAB 1: QUẢN LÝ ĐƠN HÀNG -->
      <section id="tab-orders" class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-900 p-6 rounded-3xl border border-slate-800">
          <div>
            <h2 class="text-2xl font-black text-white">Danh Sách Đơn Hàng Realtime 📦</h2>
            <p class="text-xs text-slate-400">Tự động cập nhật từ server mỗi 5 giây</p>
          </div>
          <button onclick="loadOrders()" class="bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold px-4 py-2.5 rounded-xl border border-slate-700 transition flex items-center gap-2">
            <i class="fa-solid fa-rotate-right text-rose-500"></i> Làm mới ngay
          </button>
        </div>

        <div id="admin-orders-list" class="space-y-4">
          <!-- Render danh sách đơn -->
        </div>
      </section>

      <!-- TAB 2: QUẢN LÝ QUÁN & MÓN ÁN -->
      <section id="tab-stores" class="space-y-6 hidden">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-900 p-6 rounded-3xl border border-slate-800">
          <div>
            <h2 class="text-2xl font-black text-white">Danh Sách Quán Ăn 🔥</h2>
            <p class="text-xs text-slate-400">Thêm quán mới, bật/tắt trạng thái mở cửa hoặc chỉnh sửa menu</p>
          </div>
          <button onclick="openAddStoreModal()" class="bg-rose-600 hover:bg-rose-500 text-white text-xs sm:text-sm font-extrabold px-5 py-2.5 rounded-xl transition flex items-center gap-2 shadow-lg shadow-rose-600/30">
            <i class="fa-solid fa-plus"></i> Thêm Quán Mới
          </button>
        </div>
        <div id="admin-stores-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>
      </section>
    </main>
  </div>

  <footer class="bg-slate-900 text-slate-500 border-t border-slate-800 mt-16 py-6 text-center text-xs">
    © 2026 PTITFOOD Admin Control Panel.
  </footer>

  <!-- MODAL 1: QUẢN LÝ MÓN CỦA QUÁN -->
  <div id="menu-modal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-slate-900 text-white rounded-3xl w-full max-w-3xl max-h-[90vh] overflow-y-auto shadow-2xl border border-slate-800 p-6 space-y-6 relative">
      <button onclick="closeMenuModal()" class="absolute top-5 right-5 text-slate-400 hover:text-white w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center transition">
        <i class="fa-solid fa-xmark text-lg"></i>
      </button>

      <div>
        <span id="modal-store-tag" class="text-[10px] font-bold text-rose-500 uppercase tracking-wider">RESTAURANT</span>
        <h2 id="modal-store-name" class="text-xl font-extrabold text-white">Tên Quán Ăn</h2>
      </div>

      <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800 space-y-3">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Thời Gian Giao Hàng Dự Kiến Quán</h3>
        <div class="flex flex-col sm:flex-row gap-3">
          <input type="text" id="edit-store-delivery-time" placeholder="Ví dụ: 15 - 20 phút" class="flex-1 bg-slate-900 border border-slate-800 text-white rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-rose-500 focus:outline-none">
          <button onclick="updateStoreDeliveryTime()" class="bg-slate-800 hover:bg-slate-700 text-rose-400 border border-slate-700 font-bold text-xs px-5 py-2.5 rounded-xl transition flex items-center justify-center gap-1.5">
            <i class="fa-solid fa-clock"></i> Cập Nhật
          </button>
        </div>
      </div>

      <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800 space-y-3">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Thêm Món Mới Vào Menu</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          <input type="text" id="new-item-title" placeholder="Tên món ăn *" class="sm:col-span-2 bg-slate-900 border border-slate-800 text-white rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-rose-500 focus:outline-none">
          <input type="number" id="new-item-price" placeholder="Giá tiền (Đồng) *" class="bg-slate-900 border border-slate-800 text-white rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-rose-500 focus:outline-none">
        </div>
        <button onclick="addNewFoodItem()" class="bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs px-5 py-2.5 rounded-xl transition shadow-lg shadow-rose-600/30">
          + Thêm Món Vào Menu
        </button>
      </div>

      <div class="space-y-3">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Thực Đơn Hiện Tại</h3>
        <div id="modal-menu-list" class="space-y-2 max-h-60 overflow-y-auto pr-1"></div>
      </div>
    </div>
  </div>

  <!-- MODAL 2: THÊM QUÁN MỚI -->
  <div id="add-store-modal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-slate-900 text-white rounded-3xl w-full max-w-xl shadow-2xl border border-slate-800 p-6 space-y-5 relative">
      <button onclick="closeAddStoreModal()" class="absolute top-5 right-5 text-slate-400 hover:text-white w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center transition">
        <i class="fa-solid fa-xmark text-lg"></i>
      </button>

      <h2 class="text-xl font-extrabold text-white flex items-center gap-2">
        <i class="fa-solid fa-store text-rose-500"></i> Thêm Quán Ăn Mới
      </h2>

      <div class="space-y-3">
        <div>
          <label class="text-[11px] font-bold text-slate-400 uppercase block mb-1">Tên Quán Ăn *</label>
          <input type="text" id="store-name" placeholder="Ví dụ: Cơm Tấm Đêm Man Thiện" class="w-full bg-slate-950 border border-slate-800 text-white rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-rose-500 focus:outline-none">
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="text-[11px] font-bold text-slate-400 uppercase block mb-1">Danh Mục / Tag *</label>
            <select id="store-tag" class="w-full bg-slate-950 border border-slate-800 text-white rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-rose-500 focus:outline-none">
              <option value="CƠM">CƠM</option>
              <option value="PHỞ/BÚN">PHỞ/BÚN</option>
              <option value="BÁNH MÌ">BÁNH MÌ</option>
              <option value="ĐỒ UỐNG">ĐỒ UỐNG</option>
              <option value="Trà sữa">Trà sữa</option>
            </select>
          </div>
          <div>
            <label class="text-[11px] font-bold text-slate-400 uppercase block mb-1">Thời gian giao *</label>
            <input type="text" id="store-time" placeholder="15 - 20 phút" value="15 - 20 phút" class="w-full bg-slate-950 border border-slate-800 text-white rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-rose-500 focus:outline-none">
          </div>
        </div>

        <div>
          <label class="text-[11px] font-bold text-slate-400 uppercase block mb-1">Địa Chỉ Quán *</label>
          <input type="text" id="store-address" placeholder="Ví dụ: 97 Man Thiện, P. Tăng Nhơn Phú A" class="w-full bg-slate-950 border border-slate-800 text-white rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-rose-500 focus:outline-none">
        </div>

        <div>
          <label class="text-[11px] font-bold text-slate-400 uppercase block mb-1">Link Ảnh Banner Quán (URL) *</label>
          <input type="text" id="store-image" placeholder="https://images.unsplash.com/photo-..." class="w-full bg-slate-950 border border-slate-800 text-white rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-rose-500 focus:outline-none">
        </div>

        <div>
          <label class="text-[11px] font-bold text-slate-400 uppercase block mb-1">Mô Tả Ngắn</label>
          <input type="text" id="store-desc" placeholder="Chuyên phục vụ các món ăn cày deadline..." class="w-full bg-slate-950 border border-slate-800 text-white rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-rose-500 focus:outline-none">
        </div>
      </div>

      <div class="pt-3 border-t border-slate-800 flex justify-end gap-3">
        <button onclick="closeAddStoreModal()" class="bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs px-5 py-2.5 rounded-xl transition">Hủy</button>
        <button onclick="saveNewStore()" class="bg-rose-600 hover:bg-rose-500 text-white font-extrabold text-xs px-6 py-2.5 rounded-xl transition shadow-lg shadow-rose-600/30">Lưu Quán Mới 🚀</button>
      </div>
    </div>
  </div>

  <script>
    let adminStores = [];
    let currentEditingStoreId = null;

    document.addEventListener("DOMContentLoaded", () => {
      loadOrders();
      loadStoresData();
      setInterval(loadOrders, 5000);
    });

    function switchTab(tab) {
      if (tab === 'orders') {
        document.getElementById('tab-orders').classList.remove('hidden');
        document.getElementById('tab-stores').classList.add('hidden');
        document.getElementById('tab-btn-orders').className = "bg-rose-600 text-white font-extrabold text-xs sm:text-sm px-5 py-2.5 rounded-xl shadow-lg shadow-rose-600/20 transition flex items-center gap-2";
        document.getElementById('tab-btn-stores').className = "bg-slate-900 hover:bg-slate-800 text-slate-400 font-bold text-xs sm:text-sm px-5 py-2.5 rounded-xl border border-slate-800 transition flex items-center gap-2";
      } else {
        document.getElementById('tab-orders').classList.add('hidden');
        document.getElementById('tab-stores').classList.remove('hidden');
        document.getElementById('tab-btn-stores').className = "bg-rose-600 text-white font-extrabold text-xs sm:text-sm px-5 py-2.5 rounded-xl shadow-lg shadow-rose-600/20 transition flex items-center gap-2";
        document.getElementById('tab-btn-orders').className = "bg-slate-900 hover:bg-slate-800 text-slate-400 font-bold text-xs sm:text-sm px-5 py-2.5 rounded-xl border border-slate-800 transition flex items-center gap-2";
      }
    }

    // 1. QUẢN LÝ ĐƠN HÀNG
    function loadOrders() {
      fetch('orders.json?v=' + new Date().getTime())
        .then(res => res.json())
        .then(orders => {
          const container = document.getElementById('admin-orders-list');
          
          const pendingOrders = orders.filter(o => !o.status || o.status === 'Chờ xác nhận');
          document.getElementById('pending-count-badge').innerText = pendingOrders.length;

          if (orders.length === 0) {
            container.innerHTML = `
              <div class="text-center py-12 bg-slate-900 rounded-3xl border border-slate-800 space-y-2">
                <i class="fa-solid fa-box-open text-3xl text-slate-600"></i>
                <p class="text-xs text-slate-400 font-medium">Chưa có đơn hàng nào từ khách!</p>
              </div>
            `;
            return;
          }

          container.innerHTML = orders.map((order) => {
            const currentStatus = order.status || 'Chờ xác nhận';

            let badgeColor = 'bg-amber-500/10 text-amber-400 border-amber-500/20';
            if (currentStatus === 'Đang đi lấy hàng') badgeColor = 'bg-sky-500/10 text-sky-400 border-sky-500/20';
            if (currentStatus === 'Đang giao') badgeColor = 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20';
            if (currentStatus === 'Hoàn thành') badgeColor = 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20';
            if (currentStatus === 'Đã hủy') badgeColor = 'bg-rose-500/10 text-rose-400 border-rose-500/20';

            // Tự động kiểm tra trường thời gian giao trong object order (hỗ trợ nhiều tên key)
            const deliveryTimeInfo = order.customer.deliveryTime || order.customer.time || order.deliveryTime || order.delivery_time || order.time || 'Giao ngay';

            return `
              <div class="bg-slate-900 border border-slate-800 rounded-3xl p-5 sm:p-6 space-y-4 shadow-xl">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-800 pb-3">
                  <div>
                    <div class="flex items-center gap-2">
                      <span class="font-extrabold text-base text-rose-500">${order.id}</span>
                      <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full border ${badgeColor}">
                        ${currentStatus}
                      </span>
                    </div>
                    <p class="text-[11px] text-slate-500 pt-0.5">${order.date}</p>
                  </div>

                  <div class="flex items-center gap-2 pt-2 sm:pt-0">
                    <span class="text-xs text-slate-400 font-semibold">Cập nhật:</span>
                    <select onchange="updateOrderStatus('${order.id}', this.value)" class="bg-slate-950 border border-slate-800 text-white rounded-xl px-3 py-1.5 text-xs focus:ring-2 focus:ring-rose-500 focus:outline-none font-bold">
                      <option value="Chờ xác nhận" ${currentStatus === 'Chờ xác nhận' ? 'selected' : ''}>⏳ Chờ xác nhận</option>
                      <option value="Đang đi lấy hàng" ${currentStatus === 'Đang đi lấy hàng' ? 'selected' : ''}>🚴 Đang đi lấy hàng</option>
                      <option value="Đang giao" ${currentStatus === 'Đang giao' ? 'selected' : ''}>🚀 Đang giao tận sảnh</option>
                      <option value="Hoàn thành" ${currentStatus === 'Hoàn thành' ? 'selected' : ''}>✅ Hoàn thành</option>
                      <option value="Đã hủy" ${currentStatus === 'Đã hủy' ? 'selected' : ''}>❌ Đã hủy</option>
                    </select>

                    <button onclick="confirmDeleteOrder('${order.id}')" class="bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 p-2 rounded-xl transition text-xs font-bold" title="Xóa đơn này">
                      <i class="fa-solid fa-trash-can"></i>
                    </button>
                  </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 bg-slate-950 p-3.5 rounded-2xl border border-slate-800 text-xs">
                  <div>
                    <span class="text-slate-500 block text-[10px] uppercase font-bold">Khách hàng</span>
                    <span class="font-bold text-white">${order.customer.name} - ${order.customer.phone}</span>
                  </div>
                  <div>
                    <span class="text-slate-500 block text-[10px] uppercase font-bold">Địa điểm giao</span>
                    <span class="font-bold text-rose-400">${order.customer.location}</span>
                  </div>
                  <div>
                    <span class="text-slate-500 block text-[10px] uppercase font-bold">Giờ giao</span>
                    <span class="font-bold text-amber-400 flex items-center gap-1">
                      <i class="fa-solid fa-clock text-[10px]"></i> ${deliveryTimeInfo}
                    </span>
                  </div>
                  <div>
                    <span class="text-slate-500 block text-[10px] uppercase font-bold">Thanh toán</span>
                    <span class="font-bold text-slate-200">${order.paymentMethod === 'QR' ? 'Chuyển khoản VietQR' : 'Tiền mặt (COD)'}</span>
                  </div>
                </div>

                <div class="space-y-1.5">
                  ${order.items.map(item => `
                    <div class="flex justify-between text-xs">
                      <span class="text-slate-300">${item.title || item.name} <b class="text-white">x${item.qty}</b></span>
                      <span class="font-semibold text-slate-400">${(item.price * item.qty).toLocaleString('vi-VN')}đ</span>
                    </div>
                  `).join('')}
                  ${order.customer.note ? `<p class="text-[11px] text-amber-400 italic pt-1">Ghi chú: "${order.customer.note}"</p>` : ''}
                </div>

                <div class="pt-3 border-t border-slate-800 flex items-center justify-between text-xs">
                  <span class="text-slate-400">Tổng tiền thu khách:</span>
                  <span class="text-lg font-black text-rose-500">${order.total.toLocaleString('vi-VN')}đ</span>
                </div>
              </div>
            `;
          }).join('');
        })
        .catch(err => {
          document.getElementById('admin-orders-list').innerHTML = `
            <div class="text-center py-12 bg-slate-900 rounded-3xl border border-slate-800 text-xs text-slate-400">
              Chưa có đơn hàng nào trên server.
            </div>
          `;
        });
    }

    function updateOrderStatus(orderId, newStatus) {
      fetch('update_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ orderId: orderId, status: newStatus })
      })
      .then(res => res.json())
      .then(data => {
        Swal.fire({
          icon: 'success',
          title: 'Đã cập nhật!',
          text: `Đơn hàng ${orderId} chuyển sang: ${newStatus}`,
          timer: 1500,
          showConfirmButton: false
        });
        loadOrders();
      });
    }

    function confirmDeleteOrder(orderId) {
      Swal.fire({
        title: 'Xóa đơn hàng?',
        text: `Bạn có chắc muốn xóa vĩnh viễn đơn ${orderId} khỏi server?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#334155',
        confirmButtonText: 'Xóa vĩnh viễn',
        cancelButtonText: 'Hủy'
      }).then((result) => {
        if (result.isConfirmed) {
          fetch('delete_order.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ orderId: orderId })
          })
          .then(res => res.json())
          .then(data => {
            if (data.status === 'success') {
              Swal.fire({ icon: 'success', title: 'Đã xóa!', timer: 1200, showConfirmButton: false });
              loadOrders();
            } else {
              Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Không thể xóa đơn hàng!' });
            }
          });
        }
      });
    }

    // 2. QUẢN LÝ QUÁN & MÓN
    function loadStoresData() {
      fetch('menu.json?v=' + new Date().getTime())
        .then(res => res.json())
        .then(data => {
          adminStores = data;
          renderStoresGrid();
        });
    }

    function renderStoresGrid() {
      const container = document.getElementById('admin-stores-grid');
      container.innerHTML = adminStores.map(store => {
        const isOpen = store.is_open !== undefined ? store.is_open : true;
        const storeIdStr = String(store.id);
        const deliveryTime = store.delivery_time || store.time || '15 - 20 phút';

        return `
          <div class="bg-slate-900 border border-slate-800 rounded-3xl p-5 space-y-4 shadow-xl flex flex-col justify-between relative group">
            <button onclick="confirmDeleteStore('${storeIdStr}')" class="absolute top-3 right-3 bg-rose-600/80 hover:bg-rose-600 text-white w-7 h-7 rounded-full text-xs font-bold transition flex items-center justify-center shadow-lg z-10" title="Xóa quán ăn này">
              <i class="fa-solid fa-trash-can"></i>
            </button>

            <div class="space-y-3">
              <div class="h-36 rounded-2xl overflow-hidden relative">
                <img src="${store.image || 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=600'}" alt="${store.name}" class="w-full h-full object-cover">
                <span class="absolute top-3 left-3 bg-slate-950/80 backdrop-blur-md text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">
                  ${store.tag || 'Quán'}
                </span>
                <span class="absolute bottom-3 right-3 bg-slate-950/80 backdrop-blur-md text-rose-400 text-[10px] font-bold px-2.5 py-1 rounded-full flex items-center gap-1">
                  <i class="fa-solid fa-clock text-[9px]"></i> ${deliveryTime}
                </span>
              </div>

              <div class="space-y-1">
                <h3 class="font-extrabold text-white text-base leading-snug">${store.name}</h3>
                <p class="text-xs text-slate-400 line-clamp-1">${store.address}</p>
                <div class="flex items-center gap-2 pt-1 text-xs">
                  <span class="text-rose-400 font-semibold">${store.items ? store.items.length : 0} món trong menu</span>
                  <span class="text-slate-600">•</span>
                  <span class="text-slate-400 font-medium flex items-center gap-1">
                    <i class="fa-solid fa-truck-fast text-[10px] text-rose-500"></i> ${deliveryTime}
                  </span>
                </div>
              </div>
            </div>

            <div class="pt-3 border-t border-slate-800 space-y-2">
              <button onclick="toggleStoreStatus('${storeIdStr}')" class="w-full ${isOpen ? 'bg-emerald-600/20 text-emerald-400 border-emerald-500/30' : 'bg-rose-600/20 text-rose-400 border-rose-500/30'} border font-bold text-xs py-2 rounded-xl transition flex items-center justify-center gap-2">
                <i class="fa-solid ${isOpen ? 'fa-door-open' : 'fa-door-closed'}"></i>
                <span>${isOpen ? 'Quán Đang Mở Cửa' : 'Quán Đang Đóng Cửa'}</span>
              </button>

              <button onclick="openMenuModal('${storeIdStr}')" class="w-full bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs py-2 rounded-xl transition border border-slate-700">
                Sửa thực đơn & giờ giao
              </button>
            </div>
          </div>
        `;
      }).join('');
    }

    // THÊM QUÁN MỚI
    function openAddStoreModal() {
      document.getElementById('add-store-modal').classList.remove('hidden');
    }

    function closeAddStoreModal() {
      document.getElementById('add-store-modal').classList.add('hidden');
    }

    function saveNewStore() {
      const name = document.getElementById('store-name').value.trim();
      const tag = document.getElementById('store-tag').value;
      const time = document.getElementById('store-time').value.trim();
      const address = document.getElementById('store-address').value.trim();
      const image = document.getElementById('store-image').value.trim();
      const desc = document.getElementById('store-desc').value.trim();

      if (!name || !address) {
        Swal.fire({ icon: 'error', title: 'Thiếu thông tin', text: 'Vui lòng nhập tên quán và địa chỉ!', confirmButtonColor: '#e11d48' });
        return;
      }

      const storeId = 'store-' + Date.now();

      const newStoreObj = {
        id: storeId,
        name: name,
        tag: tag,
        category: tag,
        delivery_time: time || '15 - 20 phút',
        time: time || '15 - 20 phút',
        address: address,
        image: image || 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=600',
        desc: desc || 'Món ăn ngon phục vụ sinh viên PTIT',
        rating: 5.0,
        reviews_count: 10,
        is_open: true,
        items: []
      };

      adminStores.unshift(newStoreObj);
      saveMenuToServer();

      document.getElementById('store-name').value = '';
      document.getElementById('store-address').value = '';
      document.getElementById('store-image').value = '';
      document.getElementById('store-desc').value = '';

      closeAddStoreModal();

      Swal.fire({
        icon: 'success',
        title: 'Đã thêm quán mới!',
        text: `Quán "${name}" đã được tạo thành công`,
        timer: 1500,
        showConfirmButton: false
      });
    }

    // XÓA QUÁN ÁN
    function confirmDeleteStore(storeId) {
      const store = adminStores.find(s => String(s.id) === String(storeId));
      if (!store) return;

      Swal.fire({
        title: 'Xóa quán ăn?',
        text: `Bạn có chắc muốn xóa vĩnh viễn quán "${store.name}" khỏi hệ thống?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#334155',
        confirmButtonText: 'Xóa vĩnh viễn',
        cancelButtonText: 'Hủy'
      }).then((result) => {
        if (result.isConfirmed) {
          adminStores = adminStores.filter(s => String(s.id) !== String(storeId));
          saveMenuToServer();
          Swal.fire({ icon: 'success', title: 'Đã xóa quán!', timer: 1200, showConfirmButton: false });
        }
      });
    }

    function toggleStoreStatus(storeId) {
      const store = adminStores.find(s => String(s.id) === String(storeId));
      if (store) {
        store.is_open = store.is_open !== undefined ? !store.is_open : false;
        saveMenuToServer();
      }
    }

    function openMenuModal(storeId) {
      currentEditingStoreId = storeId;
      const store = adminStores.find(s => String(s.id) === String(storeId));
      if (!store) return;

      document.getElementById('modal-store-name').innerText = store.name;
      document.getElementById('modal-store-tag').innerText = store.tag || 'Quán';
      document.getElementById('edit-store-delivery-time').value = store.delivery_time || store.time || '15 - 20 phút';
      
      renderModalMenuList(store);

      document.getElementById('menu-modal').classList.remove('hidden');
    }

    function updateStoreDeliveryTime() {
      const newTime = document.getElementById('edit-store-delivery-time').value.trim();
      if (!newTime) {
        Swal.fire({ icon: 'error', title: 'Thiếu thông tin', text: 'Vui lòng nhập thời gian giao hàng!', confirmButtonColor: '#e11d48' });
        return;
      }

      const store = adminStores.find(s => String(s.id) === String(currentEditingStoreId));
      if (store) {
        store.delivery_time = newTime;
        store.time = newTime;
        saveMenuToServer();

        Swal.fire({
          icon: 'success',
          title: 'Đã cập nhật giờ giao!',
          text: `Thời gian giao của ${store.name} chuyển thành: ${newTime}`,
          timer: 1500,
          showConfirmButton: false
        });
      }
    }

    function closeMenuModal() {
      document.getElementById('menu-modal').classList.add('hidden');
    }

    function renderModalMenuList(store) {
      const container = document.getElementById('modal-menu-list');
      if (!store.items || store.items.length === 0) {
        container.innerHTML = `<p class="text-xs text-slate-500 text-center py-4">Chưa có món nào trong thực đơn</p>`;
        return;
      }

      container.innerHTML = store.items.map((item, idx) => `
        <div class="bg-slate-950 p-3 rounded-xl border border-slate-800 flex items-center justify-between text-xs">
          <div>
            <p class="font-bold text-white">${item.name || item.title}</p>
            <p class="text-amber-400 font-semibold">${item.price.toLocaleString('vi-VN')}đ</p>
          </div>
          <button onclick="deleteFoodItem(${idx})" class="text-rose-500 hover:text-rose-400 p-1 font-bold">
            <i class="fa-solid fa-trash-can"></i>
          </button>
        </div>
      `).join('');
    }

    function addNewFoodItem() {
      const title = document.getElementById('new-item-title').value.trim();
      const price = parseInt(document.getElementById('new-item-price').value);

      if (!title || isNaN(price)) {
        Swal.fire({ icon: 'error', title: 'Thiếu thông tin', text: 'Vui lòng nhập tên món và giá tiền hợp lệ!', confirmButtonColor: '#e11d48' });
        return;
      }

      const store = adminStores.find(s => String(s.id) === String(currentEditingStoreId));
      if (store) {
        if (!store.items) store.items = [];
        
        store.items.push({ 
          id: Date.now(), 
          name: title, 
          title: title, 
          price: price 
        });
        
        document.getElementById('new-item-title').value = '';
        document.getElementById('new-item-price').value = '';
        
        renderModalMenuList(store);
        saveMenuToServer();

        Swal.fire({
          icon: 'success',
          title: 'Thêm món thành công!',
          text: `Đã thêm món "${title}" vào thực đơn`,
          timer: 1500,
          showConfirmButton: false
        });
      }
    }

    function deleteFoodItem(itemIndex) {
      const store = adminStores.find(s => String(s.id) === String(currentEditingStoreId));
      if (store && store.items) {
        store.items.splice(itemIndex, 1);
        renderModalMenuList(store);
        saveMenuToServer();
      }
    }

    function saveMenuToServer() {
      fetch('update_menu.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(adminStores)
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          renderStoresGrid();
        } else {
          Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Không thể ghi file menu.json trên server (Nhớ CHMOD 777 file menu.json)!' });
        }
      });
    }
  </script>
</body>
</html>