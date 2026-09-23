<!DOCTYPE html>
<html lang="vi" class="bg-slate-950">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PTITHCM Food Express - Trạm Cứu Đói Deadline</title>
  
  <!-- Tailwind CSS & FontAwesome CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
  
  <!-- SweetAlert2 (Dark Theme) & jQuery CDN -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" referrerpolicy="no-referrer"></script>
  <link href="//cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.js"></script>

  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
    @keyframes slotSpin {
      0% { transform: translateY(-10px); opacity: 0.3; }
      50% { transform: translateY(0); opacity: 1; }
      100% { transform: translateY(10px); opacity: 0.3; }
    }
    .slot-animate {
      animation: slotSpin 0.1s infinite linear;
    }
  </style>
</head>
<body class="bg-slate-950 text-slate-100 antialiased min-h-screen flex flex-col justify-between">

  <div>
    <!-- Header Navigation -->
    <header class="sticky top-3 z-40 px-4 max-w-7xl mx-auto">
      <div class="bg-slate-900/90 backdrop-blur-md text-white rounded-2xl px-6 py-3.5 shadow-2xl border border-slate-800 flex items-center justify-between">
        
        <!-- Brand Logo -->
        <div class="flex items-center gap-3 cursor-pointer" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
          <div class="w-10 h-10 rounded-xl bg-rose-600 flex items-center justify-center font-black text-white text-xl shadow-lg shadow-rose-600/40">
            P
          </div>
          <div>
            <span class="font-extrabold text-lg tracking-tight block leading-none">PTITHCM <span class="text-rose-500">EXPRESS</span></span>
            <span class="text-xs text-slate-400 font-medium">Trạm Ship Nội Khu 97 Man Thiện</span>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-2 sm:gap-3">
          <button onclick="openHistoryModal()" class="bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold text-xs sm:text-sm px-3.5 py-2.5 rounded-xl border border-slate-700 flex items-center gap-2 transition active:scale-95">
            <i class="fa-solid fa-clock-rotate-left text-rose-500"></i>
            <span class="hidden sm:inline">Lịch sử đơn</span>
          </button>

          <button onclick="openCartModal()" class="bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs sm:text-sm px-4 py-2.5 rounded-xl flex items-center gap-2.5 transition active:scale-95 shadow-lg shadow-rose-600/30">
            <i class="fa-solid fa-cart-shopping"></i>
            <span>Giỏ hàng</span>
            <span id="header-cart-count" class="bg-white text-rose-600 rounded-lg px-2 py-0.5 text-xs font-extrabold">0</span>
          </button>
        </div>
      </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 pt-6 space-y-12">

      <!-- HERO BANNER -->
      <section class="bg-slate-900 rounded-3xl p-6 sm:p-12 border border-slate-800 shadow-2xl relative overflow-hidden">
        <div class="max-w-2xl space-y-6 relative z-10">
          
          <div class="inline-flex items-center gap-2 bg-rose-500/10 text-rose-400 border border-rose-500/20 text-xs sm:text-sm font-bold px-4 py-1.5 rounded-xl">
            <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
            Giao siêu tốc tận sảnh 97 Man Thiện trong 20 phút
          </div>

          <h1 class="text-3xl sm:text-5xl font-black text-white tracking-tight leading-tight">
            Cơm Nóng, Trà Sữa <br>
            <span class="text-rose-500 underline decoration-rose-500/40 decoration-wavy underline-offset-4">Cứu Đói Deadline</span> PTIT
          </h1>

          <p class="text-slate-400 text-sm sm:text-base leading-relaxed font-medium">
            Bấm chọn Quán ➔ Chọn món yêu thích ➔ Đơn hàng tự động báo về Admin!
          </p>

          <div class="flex items-center gap-3 pt-1">
            <a href="#restaurant-grid" class="bg-rose-600 hover:bg-rose-500 text-white font-extrabold text-xs sm:text-sm px-6 py-3 rounded-2xl shadow-lg shadow-rose-600/30 transition active:scale-95">
                🚀 Đặt món ngay
            </a>
            <button onclick="askBudgetBeforeRandom()" class="bg-slate-800 hover:bg-slate-700 text-amber-400 border border-slate-700 font-bold text-xs sm:text-sm px-5 py-3 rounded-2xl transition flex items-center gap-2">
              <i class="fa-solid fa-wand-magic-sparkles"></i> Gợi ý món ngẫu nhiên
            </button>
            <a href="https://forms.gle/9q7WkpL8qbVLna9s5" target="_blank" class="bg-slate-800 hover:bg-slate-700 text-emerald-400 border border-slate-700 font-bold text-xs sm:text-sm px-5 py-3 rounded-2xl transition flex items-center gap-2">
              <i class="fa-solid fa-file-lines"></i> Đề xuất quán/món mới
            </a>
          </div>

          <div class="grid grid-cols-3 gap-4 pt-6 border-t border-slate-800/80">
            <div>
              <p class="text-xl sm:text-2xl font-black text-white"><span id="stat-customers">0</span>+</p>
              <p class="text-[11px] sm:text-xs text-slate-400 font-medium">Khách hàng</p>
            </div>
            <div>
              <p class="text-xl sm:text-2xl font-black text-white"><span id="stat-rating">0</span>/5 ⭐</p>
              <p class="text-[11px] sm:text-xs text-slate-400 font-medium">Đánh giá quán</p>
            </div>
            <div>
              <p class="text-xl sm:text-2xl font-black text-white"><span id="stat-time">0</span> phút</p>
              <p class="text-[11px] sm:text-xs text-slate-400 font-medium">Giao tận nơi</p>
            </div>
          </div>

        </div>

        <div class="absolute -right-16 -bottom-16 w-80 h-80 bg-rose-600/10 rounded-full blur-3xl pointer-events-none"></div>
      </section>

      <!-- Search & Quán Ăn Section -->
      <section class="space-y-6">
        <div class="bg-slate-900 p-4 sm:p-6 rounded-3xl border border-slate-800 shadow-xl space-y-4">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
              <h2 class="text-2xl font-extrabold text-white">Quán ăn nổi bật 🔥</h2>
              <p class="text-sm text-slate-400">Chọn quán ngon nạp năng lượng cày đồ án</p>
            </div>

            <div class="relative w-full sm:w-80">
              <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
              <input 
                type="text" 
                id="search-input" 
                onkeyup="filterRestaurants()" 
                placeholder="Tìm tên quán, tên món, địa chỉ..." 
                class="w-full bg-slate-950 border border-slate-800 rounded-2xl pl-11 pr-4 py-2.5 text-xs sm:text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-rose-500 transition"
              >
            </div>
          </div>

          <div class="flex items-center gap-2 overflow-x-auto pt-2 pb-1 no-scrollbar">
            <button onclick="setCategoryFilter('all', this)" class="cat-btn active bg-rose-600 text-white font-bold text-xs px-4 py-2 rounded-xl transition whitespace-nowrap">
              Tất cả
            </button>
            <button onclick="setCategoryFilter('Phở', this)" class="cat-btn bg-slate-950 text-slate-400 hover:text-white font-bold text-xs px-4 py-2 rounded-xl border border-slate-800 transition whitespace-nowrap">
              🍜 Phở / Bún
            </button>
            <button onclick="setCategoryFilter('Cơm', this)" class="cat-btn bg-slate-950 text-slate-400 hover:text-white font-bold text-xs px-4 py-2 rounded-xl border border-slate-800 transition whitespace-nowrap">
              🍚 Cơm
            </button>
            <button onclick="setCategoryFilter('Đồ uống', this)" class="cat-btn bg-slate-950 text-slate-400 hover:text-white font-bold text-xs px-4 py-2 rounded-xl border border-slate-800 transition whitespace-nowrap">
              🧋 Đồ uống / Nước
            </button>
            <button onclick="setCategoryFilter('Ăn vặt', this)" class="cat-btn bg-slate-950 text-slate-400 hover:text-white font-bold text-xs px-4 py-2 rounded-xl border border-slate-800 transition whitespace-nowrap">
              🍟 Ăn vặt
            </button>
          </div>
        </div>

        <div id="restaurant-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
          <!-- JS Render các Quán -->
        </div>
      </section>

      <!-- SECTION VÌ SAO CHỌN -->
      <section class="pt-8 pb-10 space-y-6 bg-slate-900/90 p-6 sm:p-10 rounded-3xl border border-slate-800/80 shadow-2xl">
        <div class="text-center space-y-1">
          <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Vì sao chọn PTITFOOD?</h2>
          <p class="text-xs sm:text-sm text-slate-400">Nền tảng ship đồ ăn dành riêng cho cạ cứng Học viện</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 pt-4">
          <div class="bg-slate-950/80 text-white rounded-3xl p-6 border border-slate-800 hover:border-rose-500/50 shadow-xl space-y-3 hover:-translate-y-1 transition duration-300">
            <div class="w-12 h-12 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-500 flex items-center justify-center text-xl font-bold">
              <i class="fa-solid fa-bolt"></i>
            </div>
            <h3 class="font-extrabold text-base text-white">Giao hàng nhanh</h3>
            <p class="text-xs text-slate-400 leading-relaxed font-medium">
              Đặt món ngay và nhận hàng tận sảnh A, C hoặc KTX trong thời gian tối ưu nhất.
            </p>
          </div>

          <div class="bg-slate-950/80 text-white rounded-3xl p-6 border border-slate-800 hover:border-rose-500/50 shadow-xl space-y-3 hover:-translate-y-1 transition duration-300">
            <div class="w-12 h-12 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-500 flex items-center justify-center text-xl font-bold">
              <i class="fa-solid fa-utensils"></i>
            </div>
            <h3 class="font-extrabold text-base text-white">Món ăn đa dạng</h3>
            <p class="text-xs text-slate-400 leading-relaxed font-medium">
              Hàng trăm món ăn từ các quán ruột quanh Man Thiện phù hợp mọi khẩu vị.
            </p>
          </div>

          <div class="bg-slate-950/80 text-white rounded-3xl p-6 border border-slate-800 hover:border-rose-500/50 shadow-xl space-y-3 hover:-translate-y-1 transition duration-300">
            <div class="w-12 h-12 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-500 flex items-center justify-center text-xl font-bold">
              <i class="fa-solid fa-shield-halved"></i>
            </div>
            <h3 class="font-extrabold text-base text-white">Thanh toán an toàn</h3>
            <p class="text-xs text-slate-400 leading-relaxed font-medium">
              Hệ thống VietQR tự động rõ ràng, hỗ trợ trả tiền mặt COD linh hoạt.
            </p>
          </div>

          <div class="bg-slate-950/80 text-white rounded-3xl p-6 border border-slate-800 hover:border-rose-500/50 shadow-xl space-y-3 hover:-translate-y-1 transition duration-300">
            <div class="w-12 h-12 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-500 flex items-center justify-center text-xl font-bold">
              <i class="fa-solid fa-box"></i>
            </div>
            <h3 class="font-extrabold text-base text-white">Theo dõi đơn hàng</h3>
            <p class="text-xs text-slate-400 leading-relaxed font-medium">
              Lưu lịch sử đơn chi tiết trực tiếp trên thiết bị cá nhân của bạn.
            </p>
          </div>
        </div>
      </section>

    </main>
  </div>

  <!-- FOOTER -->
  <footer class="bg-slate-900 text-white border-t border-slate-800 mt-16 pt-12 pb-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 grid grid-cols-1 md:grid-cols-4 gap-8">
      <div class="space-y-3">
        <div class="flex items-center gap-2.5">
          <div class="w-8 h-8 rounded-xl bg-rose-600 flex items-center justify-center font-black text-white text-base shadow-lg shadow-rose-600/40">P</div>
          <span class="font-extrabold text-base tracking-tight text-white">PTITHCM <span class="text-rose-500">EXPRESS</span></span>
        </div>
        <p class="text-xs text-slate-400 leading-relaxed">
          Nền tảng đặt món ăn nhanh chóng, tiện lợi và đáng tin cậy cho mọi bữa ăn cày deadline của bạn.
        </p>
      </div>

      <div class="space-y-3">
        <h4 class="font-bold text-sm text-white uppercase tracking-wider">Công ty</h4>
        <ul class="space-y-2 text-xs text-slate-400">
          <li><a href="#" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="hover:text-rose-400 transition">Trang chủ</a></li>
          <li><a href="#restaurant-grid" class="hover:text-rose-400 transition">Thực đơn</a></li>
          <li><a href="#" onclick="openCartModal()" class="hover:text-rose-400 transition">Giỏ hàng</a></li>
        </ul>
      </div>

      <div class="space-y-3">
        <h4 class="font-bold text-sm text-white uppercase tracking-wider">Hỗ trợ</h4>
        <ul class="space-y-2 text-xs text-slate-400">
          <li><a href="#" class="hover:text-rose-400 transition">Trung tâm trợ giúp</a></li>
          <li><a href="#" class="hover:text-rose-400 transition">Vận chuyển nội khu</a></li>
          <li><a href="#" class="hover:text-rose-400 transition">Chính sách bảo mật</a></li>
        </ul>
      </div>

      <div class="space-y-3">
        <h4 class="font-bold text-sm text-white uppercase tracking-wider">Liên hệ</h4>
        <ul class="space-y-2 text-xs text-slate-400">
          <li class="flex items-center gap-2"><i class="fa-regular fa-envelope text-rose-500"></i> support@ptitfood.vn</li>
          <li class="flex items-center gap-2"><i class="fa-solid fa-phone text-rose-500"></i> +84 123 456 789</li>
          <li class="flex items-center gap-2"><i class="fa-solid fa-location-dot text-rose-500"></i> 97 Man Thiện, TP. Thủ Đức, TP.HCM</li>
        </ul>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 pt-8 mt-8 border-t border-slate-800 text-center text-xs text-slate-500">
      © 2026 PTITFOOD. Mọi quyền được bảo lưu.
    </div>
  </footer>

  <!-- STICKY BOTTOM BAR -->
  <div class="fixed bottom-4 left-4 right-4 max-w-2xl mx-auto z-40">
    <div class="bg-rose-600 border-2 border-rose-400/50 text-white p-4 rounded-2xl shadow-2xl shadow-rose-950/80 flex items-center justify-between gap-4 backdrop-blur-md">
      <div>
        <p class="text-xs text-rose-100 font-bold">Đã chọn (<span id="bar-cart-count">0</span> món + 5k ship)</p>
        <p id="bar-cart-total" class="text-2xl font-black text-white drop-shadow-sm">0đ</p>
      </div>

      <button onclick="openCartModal()" class="bg-slate-900 hover:bg-slate-950 text-white font-extrabold text-sm px-6 py-3 rounded-xl transition active:scale-95 shadow-lg shadow-slate-950/40 flex items-center gap-2 border border-slate-700">
        <span>Xem Giỏ Hàng & Thanh Toán</span>
        <i class="fa-solid fa-arrow-right text-xs text-rose-500"></i>
      </button>
    </div>
  </div>

  <!-- 💬 BONG BÓNG CHAT REALTIME & NÚT LOGO ZALO SUPPORT -->
  <div class="fixed bottom-24 right-4 z-50 flex flex-col items-end gap-3">
    
    <!-- CỬA SỔ CHAT -->
    <div id="chat-box" class="hidden w-80 sm:w-96 bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl overflow-hidden flex flex-col h-96 transition duration-300">
      <!-- Chat Header -->
      <div class="bg-slate-950 p-3.5 border-b border-slate-800 flex items-center justify-between">
        <div class="flex items-center gap-2.5">
          <div class="relative">
            <div class="w-8 h-8 rounded-full bg-rose-600 flex items-center justify-center font-bold text-xs text-white">P</div>
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 absolute bottom-0 right-0 border-2 border-slate-950"></span>
          </div>
          <div>
            <h4 class="text-xs font-extrabold text-white flex items-center gap-1.5">
              <span>Hỗ trợ PTIT Express</span>
            </h4>
            <span id="chat-user-display" class="text-[10px] text-slate-400 block">Đang kết nối...</span>
          </div>
        </div>

        <button onclick="toggleChat()" class="text-slate-400 hover:text-white w-7 h-7 rounded-full bg-slate-800 flex items-center justify-center text-xs">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>

      <!-- Chat Messages Container -->
      <div id="chat-messages" class="flex-1 p-3.5 overflow-y-auto space-y-3 text-xs">
        <p class="text-[10px] text-slate-500 text-center">--- Bắt đầu trò chuyện ---</p>
      </div>

      <!-- Chat Input Form -->
      <form onsubmit="sendChatMessage(event)" class="p-2.5 bg-slate-950 border-t border-slate-800 flex items-center gap-2">
        <input type="text" id="chat-input" placeholder="Nhập tin nhắn..." class="flex-1 bg-slate-900 border border-slate-800 text-white rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-rose-500">
        <button type="submit" class="bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold px-3 py-2 rounded-xl transition">
          <i class="fa-solid fa-paper-plane"></i>
        </button>
      </form>
    </div>

    <!-- 🔥 NÚT HÌNH LOGO ZALO CỐ ĐỊNH PHÍA TRÊN NÚT CHAT -->
    <a href="https://zalo.me/0769867352" target="_blank" 
       title="Hỗ trợ qua Zalo"
       class="w-12 h-12 rounded-full bg-[#0068FF] hover:bg-blue-600 text-white shadow-2xl flex items-center justify-center transition active:scale-90 border-2 border-slate-800 p-2.5">
      <!-- LOGO ZALO VECTOR SẮC NÉT -->
      <img src="https://upload.wikimedia.org/wikipedia/commons/9/91/Icon_of_Zalo.svg" alt="Zalo" class="w-full h-full object-contain">
    </a>

    <!-- Nút Bật/Tắt Chat -->
    <button onclick="toggleChat()" class="w-12 h-12 rounded-full bg-rose-600 hover:bg-rose-500 text-white shadow-2xl flex items-center justify-center text-xl transition active:scale-90 border-2 border-slate-800">
      <i class="fa-solid fa-comments"></i>
    </button>
  </div>

  <!-- Modal Giỏ Hàng -->
  <div id="cart-modal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-slate-900 text-white rounded-3xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl border border-slate-800 p-6 space-y-6 relative">
      <button onclick="closeCartModal()" class="absolute top-5 right-5 text-slate-400 hover:text-white w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center transition">
        <i class="fa-solid fa-xmark text-lg"></i>
      </button>

      <h2 class="text-xl font-extrabold text-white flex items-center gap-2">
        <i class="fa-solid fa-bag-shopping text-rose-500"></i> Giỏ hàng của bạn
      </h2>

      <div id="modal-cart-items" class="space-y-3 divide-y divide-slate-800 max-h-48 overflow-y-auto pr-1"></div>

      <!-- Mã Giảm Giá -->
      <div class="flex items-center gap-2">
        <input type="text" id="voucher-code" placeholder="Nhập mã voucher (VD: PTIT20K)" class="w-full bg-slate-950 border border-slate-800 text-white rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-rose-500 focus:outline-none uppercase font-bold">
        <button onclick="applyVoucher()" class="bg-slate-800 hover:bg-slate-700 text-rose-400 border border-slate-700 font-bold text-xs px-4 py-2 rounded-xl transition whitespace-nowrap">Áp dụng</button>
      </div>

      <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800 space-y-3">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Thông tin giao hàng</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <input type="text" id="cust-name" placeholder="Họ tên / Nickname *" class="w-full bg-slate-900 border border-slate-800 text-white rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-rose-500 focus:outline-none">
          <input type="tel" id="cust-phone" placeholder="Số điện thoại / Zalo *" class="w-full bg-slate-900 border border-slate-800 text-white rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-rose-500 focus:outline-none">
        </div>
        <select id="cust-location" class="w-full bg-slate-900 border border-slate-800 text-white rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-rose-500 focus:outline-none">
          <option value="Sảnh A - 97 Man Thiện">Sảnh A - 97 Man Thiện</option>
          <option value="Sảnh C - 97 Man Thiện">Sảnh C - 97 Man Thiện</option>
          <option value="Khu Tự Học / Thư Viện">Khu Tự Học / Thư Viện</option>
          <option value="KTX Khối A">KTX Khu I</option>
          <option value="KTX Khối B">KTX Khu J</option>
        </select>

        <div class="space-y-2 pt-1">
          <label class="text-xs font-bold text-slate-400 flex items-center gap-1.5 uppercase tracking-wider">
            <i class="fa-solid fa-clock text-rose-500"></i> Thời gian nhận hàng:
          </label>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
            <select id="cust-delivery-type" onchange="toggleCustomTimeInput()" class="w-full bg-slate-900 border border-slate-800 text-white rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-rose-500 focus:outline-none">
              <option value="ASAP">🚀 Giao ngay (Càng sớm càng tốt)</option>
              <option value="SCHEDULED">⏰ Hẹn giờ cụ thể (Định dạng 24h)</option>
            </select>
            <input type="time" id="cust-delivery-time" step="60" class="hidden w-full bg-slate-900 border border-slate-800 text-white rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-rose-500 focus:outline-none">
          </div>
        </div>

        <textarea id="cust-note" rows="2" placeholder="Ghi chú thêm (Ví dụ: Nhiều đá, không lấy hành...)" class="w-full bg-slate-900 border border-slate-800 text-white rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-rose-500 focus:outline-none"></textarea>
      </div>

      <div class="space-y-2">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Phương thức thanh toán</h3>
        <div class="grid grid-cols-2 gap-3">
          <label class="border border-slate-800 rounded-xl p-3 flex items-center gap-2 cursor-pointer hover:border-rose-500 transition has-[:checked]:border-rose-500 has-[:checked]:bg-rose-500/10">
            <input type="radio" name="payment-method" value="QR" checked class="accent-rose-500">
            <div>
              <p class="text-xs font-bold text-white">Chuyển khoản QR</p>
              <p class="text-[10px] text-slate-400">VietQR / MB / Techcombank</p>
            </div>
          </label>
          <label class="border border-slate-800 rounded-xl p-3 flex items-center gap-2 cursor-pointer hover:border-rose-500 transition has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50/10">
            <input type="radio" name="payment-method" value="COD" class="accent-rose-500">
            <div>
              <p class="text-xs font-bold text-white">Tiền mặt (COD)</p>
              <p class="text-[10px] text-slate-400">Trả khi nhận món</p>
            </div>
          </label>
        </div>
      </div>

      <div class="pt-4 border-t border-slate-800 flex items-center justify-between">
        <div>
          <p class="text-xs text-slate-400">Tổng thanh toán (gồm ship - giảm giá):</p>
          <p id="modal-grand-total" class="text-2xl font-black text-rose-500">0đ</p>
        </div>
        <button onclick="processCheckout()" class="bg-rose-600 hover:bg-rose-500 text-white font-extrabold text-sm px-6 py-3 rounded-xl transition active:scale-95 shadow-lg shadow-rose-600/30">
          Xác Nhận Đặt Hàng 🚀
        </button>
      </div>

    </div>
  </div>

  <!-- Modal Lịch Sử Đơn Hàng -->
  <div id="history-modal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-slate-900 text-white rounded-3xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl border border-slate-800 p-6 space-y-5 relative">
      <button onclick="closeHistoryModal()" class="absolute top-5 right-5 text-slate-400 hover:text-white w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center transition">
        <i class="fa-solid fa-xmark text-lg"></i>
      </button>

      <div class="flex items-center justify-between">
        <h2 class="text-xl font-extrabold text-white flex items-center gap-2">
          <i class="fa-solid fa-clock-rotate-left text-rose-500"></i> Lịch sử đặt hàng
        </h2>
        <button onclick="clearHistory()" class="text-xs text-rose-500 hover:underline font-semibold pr-8">Xóa lịch sử</button>
      </div>

      <div id="history-list" class="space-y-4 max-h-[60vh] overflow-y-auto pr-1"></div>

    </div>
  </div>

  <!-- JavaScript Scripting -->
  <script>
    let restaurantsData = [];
    let currentCategory = 'all';
    let discountAmount = 0;
    const SHIP_FEE = 5000;
    let chatInterval = null;

    // LẤY HOẶC TẠO CHAT USER ID DUY NHẤT
    function getChatUserId() {
      let uid = localStorage.getItem('ptit_chat_user_id');
      if (!uid) {
        uid = 'user_' + Math.random().toString(36).substring(2, 9);
        localStorage.setItem('ptit_chat_user_id', uid);
      }
      return uid;
    }

    // LẤY TÊN HIỂN THỊ (Lưu vĩnh viễn ở localStorage)
    function getChatName() {
      return localStorage.getItem('ptit_cust_name') || localStorage.getItem('ptit_chat_nickname') || null;
    }

    document.addEventListener("DOMContentLoaded", () => {
      showWelcomePopup();
      animateCounters();

      const savedName = getChatName();
      const savedPhone = localStorage.getItem('ptit_cust_phone');
      if (savedName) document.getElementById('cust-name').value = savedName;
      if (savedPhone) document.getElementById('cust-phone').value = savedPhone;

      fetch('menu.json')
        .then(response => response.json())
        .then(data => {
          restaurantsData = data;
          renderRestaurantGrid(restaurantsData);
          updateUI();
        })
        .catch(err => {
          console.error(err);
          document.getElementById('restaurant-grid').innerHTML = `
            <div class="col-span-full text-center py-10 text-rose-500 font-semibold text-sm">
              Không thể tải file menu.json!
            </div>
          `;
        });

      setInterval(() => {
        if (!document.getElementById('history-modal').classList.contains('hidden')) {
          renderHistoryList();
        }
      }, 5000);
    });

    // CHỈ HỎI TÊN 1 LẦN DUY NHẤT VÀ LƯU LOCALSTORAGE VĨNH VIỄN
    async function ensureNickname() {
      let currentName = getChatName();
      if (!currentName) {
        const { value: name } = await Swal.fire({
          title: '💬 Nhập Tên / Nickname',
          input: 'text',
          inputLabel: 'Tên hiển thị khi chat & đặt hàng:',
          inputPlaceholder: 'Ví dụ: quangdz, n26dct,...',
          showCancelButton: true,
          confirmButtonText: 'Lưu Tên 🚀',
          cancelButtonText: 'Để sau',
          confirmButtonColor: '#e11d48',
          cancelButtonColor: '#334155',
          allowOutsideClick: false,
          inputValidator: (val) => {
            if (!val || !val.trim()) return 'Vui lòng nhập tên!';
            if (/[<>]/.test(val)) return 'Tên không được chứa ký tự < hoặc >!';
          }
        });

        if (name) {
          const trimmedName = name.trim();
          localStorage.setItem('ptit_chat_nickname', trimmedName);
          localStorage.setItem('ptit_cust_name', trimmedName);
          document.getElementById('cust-name').value = trimmedName;
          return trimmedName;
        } else {
          return null;
        }
      }
      return currentName;
    }

    function updateChatUserDisplay() {
      const name = getChatName() || 'Khách vãng lai';
      const disp = document.getElementById('chat-user-display');
      if (disp) disp.innerText = `Đang chat: ${name}`;
    }

    async function toggleChat() {
      const chatBox = document.getElementById('chat-box');
      const isOpening = chatBox.classList.contains('hidden');

      if (isOpening) {
        const name = await ensureNickname();
        if (!name) return;

        updateChatUserDisplay();
        chatBox.classList.remove('hidden');
        loadChatMessages();

        if (!chatInterval) {
          chatInterval = setInterval(loadChatMessages, 3000);
        }
      } else {
        chatBox.classList.add('hidden');
        if (chatInterval) {
          clearInterval(chatInterval);
          chatInterval = null;
        }
      }
    }

    function loadChatMessages() {
      fetch('chat.php')
        .then(res => res.json())
        .then(messages => {
          const container = document.getElementById('chat-messages');
          const myUserId = getChatUserId();

          if (!messages || messages.length === 0) {
            container.innerHTML = `<p class="text-[10px] text-slate-500 text-center py-4">--- Chưa có tin nhắn nào ---</p>`;
            return;
          }

          container.innerHTML = messages.map(msg => {
            const isMe = msg.sender_id === myUserId;
            const isAdmin = (msg.role === 'admin' || msg.sender === 'Admin' || msg.sender === 'Admin PTIT' || msg.sender === 'HỆ THỐNG PTIT EXPRESS');

            let bubbleClass = 'bg-slate-800 text-slate-200 rounded-2xl rounded-tl-none border border-slate-700';
            let badgeHtml = '';

            if (isAdmin) {
              bubbleClass = 'bg-amber-500/10 text-amber-300 border-2 border-amber-500/60 rounded-2xl rounded-tl-none shadow-lg shadow-amber-500/20';
              badgeHtml = `<span class="bg-amber-400 text-slate-950 text-[9px] font-black px-1.5 py-0.5 rounded ml-1 uppercase shadow-md">ADMIN</span>`;
            } else if (isMe) {
              bubbleClass = 'bg-rose-600 text-white rounded-2xl rounded-tr-none';
            }

            return `
              <div class="flex flex-col ${isMe ? 'items-end' : 'items-start'}">
                <span class="text-[9px] text-slate-500 px-1 flex items-center">
                  ${msg.sender}${badgeHtml} • ${msg.time}
                </span>
                <div class="${bubbleClass} px-3 py-2 max-w-[85%] break-words leading-relaxed whitespace-pre-line mt-0.5">
                  ${msg.text}
                </div>
              </div>
            `;
          }).join('');

          container.scrollTop = container.scrollHeight;
        })
        .catch(err => console.warn("Lỗi load chat:", err));
    }

    async function sendChatMessage(e) {
      e.preventDefault();
      const input = document.getElementById('chat-input');
      const text = input.value.trim();

      if (!text) return;

      let sender = getChatName();
      if (!sender) {
        sender = await ensureNickname();
        if (!sender) return;
      }

      if (/[<>]/.test(text)) {
        Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Nội dung chứa ký tự không hợp lệ!', confirmButtonColor: '#e11d48' });
        return;
      }

      input.value = '';

      try {
        await fetch('chat.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            sender: sender,
            sender_id: getChatUserId(),
            text: text,
            role: 'user'
          })
        });
        loadChatMessages();
      } catch (err) {
        console.error("Gửi tin nhắn thất bại:", err);
      }
    }

    function toggleCustomTimeInput() {
      const type = document.getElementById('cust-delivery-type').value;
      const timeInput = document.getElementById('cust-delivery-time');
      if (type === 'SCHEDULED') {
        timeInput.classList.remove('hidden');
        if (!timeInput.value) {
          const now = new Date();
          const hours = String(now.getHours()).padStart(2, '0');
          const minutes = String(now.getMinutes()).padStart(2, '0');
          timeInput.value = `${hours}:${minutes}`;
        }
      } else {
        timeInput.classList.add('hidden');
      }
    }

    function showWelcomePopup() {
      const hideUntil = localStorage.getItem('ptit_hide_welcome_time');
      const now = new Date().getTime();

      if (hideUntil && now < parseInt(hideUntil)) {
        return;
      }

      Swal.fire({
        title: '🔥 PTITHCM Express Chào Bạn!',
        html: `
          <div class="space-y-3 text-left pt-2">
            <div class="bg-rose-500/10 border border-rose-500/20 rounded-2xl p-4 text-xs space-y-2">
              <p class="font-bold text-rose-400">⚡ Trụ sở Ship Nội Khu 97 Man Thiện</p>
              <p class="text-slate-300 leading-relaxed">
                • Giao đồ ăn siêu tốc tận sảnh A, C & KTX trong <b>20 phút</b>.<br>
                • Nhập mã <b>DEAL2K</b> để giảm ngay 2.000đ tiền ship!
              </p>
            </div>
            <p class="text-xs text-slate-400 text-center">Chúc sếp nạp đủ năng lượng cày deadline mượt mà 🚀</p>
          </div>
        `,
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Đặt Món Ngay 😋',
        cancelButtonText: 'Tắt trong 30 phút 🔕',
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#334155',
        allowOutsideClick: false
      }).then((result) => {
        if (result.dismiss === Swal.DismissReason.cancel) {
          const HIDE_DURATION = 30 * 60 * 1000;
          const expiryTime = new Date().getTime() + HIDE_DURATION;
          localStorage.setItem('ptit_hide_welcome_time', expiryTime.toString());
        }
      });
    }

    function animateCounters() {
      countUp('stat-customers', 50, 2500, 0); 
      countUp('stat-rating', 4, 1000, 1);     
      countUp('stat-time', 20, 2500, 0);     
    }

    function countUp(elementId, target, duration, decimals = 0) {
      const el = document.getElementById(elementId);
      if (!el) return;
      const start = 0;
      const startTime = performance.now();

      function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const easeOut = 1 - Math.pow(1 - progress, 3);
        const current = start + (target - start) * easeOut;
        el.innerText = decimals > 0 ? current.toFixed(decimals) : Math.floor(current);

        if (progress < 1) {
          requestAnimationFrame(update);
        } else {
          el.innerText = decimals > 0 ? target.toFixed(decimals) : target;
          if (elementId === 'stat-rating') el.innerText = '4';
        }
      }
      requestAnimationFrame(update);
    }

    setTimeout(() => {
      const r = document.getElementById('stat-rating');
      if(r) r.innerText = '4.8';
    }, 1100);

    function askBudgetBeforeRandom() {
      if (!restaurantsData.length) return;

      Swal.fire({
        title: '🎲 Chọn Ngân Sách Của Bạn',
        html: `
          <div class="space-y-3 pt-2 text-left">
            <p class="text-xs text-slate-400 text-center">Hệ thống sẽ lọc món hợp túi tiền trước khi quay ngẫu nhiên!</p>
            <select id="swal-budget-select" class="w-full bg-slate-950 border border-slate-800 text-white rounded-xl p-3 text-xs focus:ring-2 focus:ring-rose-500 focus:outline-none">
              <option value="0">Tất cả mức giá (Tùy duyên)</option>
              <option value="25000">Dưới 25.000đ (Tiết kiệm)</option>
              <option value="40000">Dưới 40.000đ (Chuẩn sinh viên)</option>
              <option value="60000">Dưới 60.000đ (Thả ga cày đồ án)</option>
            </select>
          </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Bắt đầu quay 🎰',
        cancelButtonText: 'Hủy',
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#334155'
      }).then((result) => {
        if (result.isConfirmed) {
          const selectedBudget = parseInt(document.getElementById('swal-budget-select').value);
          randomFoodPicker(selectedBudget);
        }
      });
    }

    function randomFoodPicker(maxBudget = 0) {
      if (!restaurantsData.length) return;

      let allItems = [];
      restaurantsData.forEach(res => {
        res.items.forEach(item => {
          const price = item.price;
          if (maxBudget === 0 || price <= maxBudget) {
            allItems.push({
              resId: res.id,
              resName: res.name,
              title: item.title || item.name,
              price: price
            });
          }
        });
      });

      if (!allItems.length) {
        Swal.fire({
          icon: 'warning',
          title: 'Không tìm thấy món!',
          text: 'Không có món nào phù hợp với ngân sách bạn chọn. Vui lòng thử mức giá khác nhé!',
          confirmButtonColor: '#e11d48'
        });
        return;
      }

      Swal.fire({
        title: '🎲 Đang chọn món ăn...',
        html: `
          <div class="py-4 space-y-3">
            <div class="bg-slate-950 p-5 rounded-2xl border border-rose-500/30 shadow-inner min-h-[90px] flex flex-col justify-center items-center">
              <span id="slot-res-name" class="text-[11px] font-extrabold text-rose-500 uppercase tracking-wider block">Đang tìm quán...</span>
              <p id="slot-food-title" class="text-lg font-black text-white slot-animate my-1">???</p>
              <span id="slot-food-price" class="text-xs font-bold text-amber-400 block">...</span>
            </div>
            <p class="text-xs text-slate-400">Đang load thực đơn PTIT...</p>
          </div>
        `,
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
          let counter = 0;
          const totalDuration = 1500;
          const intervalTime = 70;

          const interval = setInterval(() => {
            const temp = allItems[Math.floor(Math.random() * allItems.length)];
            document.getElementById('slot-res-name').innerText = temp.resName;
            document.getElementById('slot-food-title').innerText = temp.title;
            document.getElementById('slot-food-price').innerText = temp.price.toLocaleString('vi-VN') + 'đ';
            counter += intervalTime;

            if (counter >= totalDuration) {
              clearInterval(interval);
              const finalChoice = allItems[Math.floor(Math.random() * allItems.length)];
              
              Swal.fire({
                title: '🎉 Món Ngon Cho Sếp!',
                html: `
                  <div class="space-y-3 text-left pt-2">
                    <p class="text-xs text-slate-400">Hệ thống đã chọn món này cho bạn:</p>
                    <div class="bg-slate-950 p-4 rounded-2xl border border-rose-500/50 space-y-1">
                      <span class="text-[10px] font-bold text-rose-500 uppercase">${finalChoice.resName}</span>
                      <p class="text-base font-extrabold text-white">${finalChoice.title}</p>
                      <p class="text-xs font-bold text-amber-400">${finalChoice.price.toLocaleString('vi-VN')}đ</p>
                    </div>
                  </div>
                `,
                icon: 'success',
                showCancelButton: true,
                confirmButtonText: 'Xem quán này ngay 🚀',
                cancelButtonText: 'Thử lại 🎲',
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#334155'
              }).then((res) => {
                if (res.isConfirmed) {
                  window.location.href = `product-detail.php?id=${finalChoice.resId}`;
                } else if (res.dismiss === Swal.DismissReason.cancel) {
                  randomFoodPicker(maxBudget);
                }
              });
            }
          }, intervalTime);
        }
      });
    }

    function setCategoryFilter(category, btn) {
      currentCategory = category;
      document.querySelectorAll('.cat-btn').forEach(b => {
        b.classList.remove('bg-rose-600', 'text-white');
        b.classList.add('bg-slate-950', 'text-slate-400', 'border', 'border-slate-800');
      });
      btn.classList.remove('bg-slate-950', 'text-slate-400', 'border', 'border-slate-800');
      btn.classList.add('bg-rose-600', 'text-white');
      filterRestaurants();
    }

    function renderRestaurantGrid(items) {
      const grid = document.getElementById('restaurant-grid');
      if (items.length === 0) {
        grid.innerHTML = `
          <div class="col-span-full text-center py-12 space-y-3 bg-slate-900 rounded-3xl border border-slate-800">
            <i class="fa-solid fa-store text-4xl text-slate-600"></i>
            <p class="text-sm font-semibold text-slate-400">Không tìm thấy quán ăn nào phù hợp!</p>
          </div>
        `;
        return;
      }

      grid.innerHTML = items.map(restaurant => {
        const minPrice = Math.min(...restaurant.items.map(i => i.price));
        const isOpen = restaurant.is_open !== undefined ? restaurant.is_open : true;

        return `
          <div onclick="${isOpen ? `window.location.href='product-detail.php?id=${restaurant.id}'` : ''}" 
            class="bg-slate-900 text-white rounded-3xl overflow-hidden border border-slate-800 shadow-xl ${isOpen ? 'hover:border-rose-500/50 cursor-pointer' : 'opacity-60 cursor-not-allowed'} transition group flex flex-col justify-between">
            <div>
              <div class="h-48 rounded-2xl overflow-hidden m-2 relative">
                <img src="${restaurant.image}" alt="${restaurant.name}" class="w-full h-full object-cover ${isOpen ? 'group-hover:scale-105' : 'grayscale'} transition duration-300">
                
                <span class="absolute top-3 left-3 bg-slate-950/80 backdrop-blur-md text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                  ${restaurant.tag}
                </span>

                ${!isOpen ? `
                  <span class="absolute inset-0 bg-slate-950/70 backdrop-blur-sm flex items-center justify-center font-extrabold text-rose-400 text-sm tracking-wider uppercase">
                    Tạm Đóng Cửa
                  </span>
                ` : ''}
              </div>

              <div class="px-4 pt-2 flex items-center justify-between text-xs">
                <div class="flex items-center gap-1 font-extrabold text-amber-400">
                  <i class="fa-solid fa-star"></i>
                  <span>★ ${restaurant.rating ? restaurant.rating.toFixed(1) : '5.0'}</span>
                </div>
                <span class="text-slate-400 text-[11px]">(${restaurant.reviews_count || 0} đánh giá)</span>
              </div>

              <div class="p-4 space-y-1.5">
                <span class="text-[10px] font-bold tracking-wider text-rose-500 uppercase">RESTAURANT</span>
                <h3 class="font-extrabold text-white text-base leading-snug line-clamp-1">${restaurant.name}</h3>
                <p class="text-xs text-slate-400 flex items-center gap-1.5 line-clamp-1">
                  <i class="fa-solid fa-location-dot text-rose-500 text-[11px]"></i>
                  <span>${restaurant.address}</span>
                </p>
              </div>
            </div>

            <div class="p-4 pt-0 space-y-3">
              <div class="flex items-center justify-between pt-2 border-t border-slate-800">
                <span class="text-lg font-black text-white">Từ ${minPrice.toLocaleString('vi-VN')} đ</span>
                <div class="text-right">
                  <span class="text-[9px] text-slate-400 block uppercase">Giao</span>
                  <span class="text-xs font-semibold text-slate-300">${restaurant.delivery_time}</span>
                </div>
              </div>

              <button class="w-full ${isOpen ? 'bg-rose-600 group-hover:bg-rose-500 shadow-rose-600/30' : 'bg-slate-800 text-slate-500 shadow-none'} text-white font-extrabold text-sm py-2.5 rounded-2xl transition active:scale-95 shadow-md">
                ${isOpen ? 'Đặt món' : 'Tạm ngưng nhận đơn'}
              </button>
            </div>
          </div>
        `;
      }).join('');
    }

    function filterRestaurants() {
      const query = document.getElementById('search-input').value.toLowerCase().trim();
      const filtered = restaurantsData.filter(r => {
        const matchesQuery = r.name.toLowerCase().includes(query) || 
                             r.desc.toLowerCase().includes(query) ||
                             r.address.toLowerCase().includes(query) ||
                             r.items.some(i => (i.title || i.name) && (i.title || i.name).toLowerCase().includes(query));
        
        const matchesCategory = currentCategory === 'all' || 
                                (r.tag && r.tag.toLowerCase().includes(currentCategory.toLowerCase())) ||
                                (r.category && r.category.toLowerCase().includes(currentCategory.toLowerCase()));

        return matchesQuery && matchesCategory;
      });
      renderRestaurantGrid(filtered);
    }

    function applyVoucher() {
      const code = document.getElementById('voucher-code').value.trim().toUpperCase();
      if (code === 'PTIT5K') {
        discountAmount = 5000;
        Swal.fire({ icon: 'success', title: 'Áp dụng thành công!', text: 'Đã giảm 5.000đ tiền phí ship!', confirmButtonColor: '#e11d48' });
      } else if (code === 'DEAL2K') {
        discountAmount = 2000;
        Swal.fire({ icon: 'success', title: 'Áp dụng thành công!', text: 'Đã giảm 2.000đ trực tiếp vào đơn!', confirmButtonColor: '#e11d48' });
      } else {
        discountAmount = 0;
        Swal.fire({ icon: 'error', title: 'Mã không hợp lệ', text: 'Thử lại mã khác nhé!', confirmButtonColor: '#e11d48' });
      }
      updateUI();
    }

    function getCart() {
      return JSON.parse(localStorage.getItem('ptit_cart') || '[]');
    }

    function updateUI() {
      const cart = getCart();
      const count = cart.reduce((sum, i) => sum + i.qty, 0);
      const itemsTotal = cart.reduce((sum, i) => sum + (i.price * i.qty), 0);
      const grandTotal = itemsTotal > 0 ? Math.max(0, itemsTotal + SHIP_FEE - discountAmount) : 0;

      document.getElementById('header-cart-count').innerText = count;
      document.getElementById('bar-cart-count').innerText = count;
      document.getElementById('bar-cart-total').innerText = grandTotal.toLocaleString('vi-VN') + 'đ';
      document.getElementById('modal-grand-total').innerText = grandTotal.toLocaleString('vi-VN') + 'đ';
    }

    function changeQty(index, delta) {
      let cart = getCart();
      cart[index].qty += delta;
      if (cart[index].qty <= 0) {
        cart.splice(index, 1);
      }
      localStorage.setItem('ptit_cart', JSON.stringify(cart));
      updateUI();
      renderModalCart();
    }

    function openCartModal() {
      const cart = getCart();
      if (cart.length === 0) {
        Swal.fire({
          icon: 'warning',
          title: 'Giỏ hàng trống',
          text: 'Sếp chưa chọn món nào hết!',
          confirmButtonColor: '#e11d48'
        });
        return;
      }
      renderModalCart();
      document.getElementById('cart-modal').classList.remove('hidden');
    }

    function closeCartModal() {
      document.getElementById('cart-modal').classList.add('hidden');
    }

    function renderModalCart() {
      const container = document.getElementById('modal-cart-items');
      const cart = getCart();

      if (cart.length === 0) {
        container.innerHTML = `<p class="text-xs text-slate-500 text-center py-4">Giỏ hàng đang trống</p>`;
        return;
      }

      container.innerHTML = cart.map((item, idx) => `
        <div class="flex items-center justify-between pt-2">
          <div>
            <p class="text-xs font-bold text-white">${item.title}</p>
            <p class="text-[11px] text-rose-500 font-semibold">${(item.price * item.qty).toLocaleString('vi-VN')}đ</p>
          </div>
          <div class="flex items-center gap-2 bg-slate-800 rounded-lg p-1">
            <button onclick="changeQty(${idx}, -1)" class="w-6 h-6 rounded bg-slate-700 font-bold text-xs text-white">-</button>
            <span class="text-xs font-bold px-1 text-white">${item.qty}</span>
            <button onclick="changeQty(${idx}, 1)" class="w-6 h-6 rounded bg-slate-700 font-bold text-xs text-white">+</button>
          </div>
        </div>
      `).join('');
    }

    async function sendNotification(order) {
      try {
        const res = await fetch('send.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(order)
        });
        const data = await res.json();
        return data;
      } catch (err) {
        console.warn("⚠️ Bắn tin nhắn chéo lỗi:", err);
        return { success: false, message: 'Không thể kết nối đến máy chủ xử lý đơn hàng.' };
      }
    }

    async function processCheckout() {
      const name = document.getElementById('cust-name').value.trim();
      const phone = document.getElementById('cust-phone').value.trim();
      const location = document.getElementById('cust-location').value;
      const note = document.getElementById('cust-note').value.trim();
      const payMethod = document.querySelector('input[name="payment-method"]:checked').value;

      if (/[<>]/.test(name) || /[<>]/.test(phone) || /[<>]/.test(note)) {
        Swal.fire({
          icon: 'error',
          title: 'Thông tin không hợp lệ!',
          text: 'Vui lòng không sử dụng các ký tự đặc biệt dạng mã (< hoặc >) trong thông tin đặt hàng!',
          confirmButtonColor: '#e11d48'
        });
        return;
      }

      if (!name || !phone) {
        Swal.fire({ icon: 'error', title: 'Thiếu thông tin', text: 'Vui lòng nhập Họ tên và Số điện thoại!', confirmButtonColor: '#e11d48' });
        return;
      }

      const cart = getCart();
      if (cart.length === 0) {
        Swal.fire({ icon: 'error', title: 'Giỏ hàng trống', text: 'Vui lòng chọn món trước khi đặt hàng!', confirmButtonColor: '#e11d48' });
        return;
      }

      const delType = document.getElementById('cust-delivery-type').value;
      const delTimeInput = document.getElementById('cust-delivery-time').value.trim();
      
      let deliveryTimeText = "Giao ngay (Càng sớm càng tốt)";
      if (delType === 'SCHEDULED') {
        if (!delTimeInput) {
          Swal.fire({ icon: 'error', title: 'Thiếu giờ giao', text: 'Vui lòng chọn giờ cụ thể muốn nhận hàng!', confirmButtonColor: '#e11d48' });
          return;
        }
        
        const timeParts = delTimeInput.split(':');
        if (timeParts.length === 2) {
          const formattedHours = String(timeParts[0]).padStart(2, '0');
          const formattedMinutes = String(timeParts[1]).padStart(2, '0');
          deliveryTimeText = `Hẹn lúc ${formattedHours}:${formattedMinutes}`;
        } else {
          deliveryTimeText = `Hẹn lúc ${delTimeInput}`;
        }
      }

      Swal.fire({
        title: 'Đang xử lý đơn hàng...',
        text: 'Vui lòng chờ trong giây lát',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
      });

      const itemsTotal = cart.reduce((sum, i) => sum + (i.price * i.qty), 0);
      const grandTotal = Math.max(0, itemsTotal + SHIP_FEE - discountAmount);
      const orderId = 'PTIT-' + Math.floor(100000 + Math.random() * 900000);

      const newOrder = {
        id: orderId,
        date: new Date().toLocaleString('vi-VN'),
        customer: { 
          name, 
          phone, 
          location, 
          note,
          deliveryTime: deliveryTimeText
        },
        items: [...cart],
        paymentMethod: payMethod,
        discount: discountAmount,
        total: grandTotal,
        status: 'Chờ xác nhận'
      };

      const serverResult = await sendNotification(newOrder);

      if (!serverResult || serverResult.success !== true) {
        Swal.fire({
          icon: 'error',
          title: 'Đặt hàng thất bại!',
          text: serverResult.message || 'Máy chủ xử lý đơn từ chối tiếp nhận. Vui lòng kiểm tra lại thông tin!',
          confirmButtonColor: '#e11d48'
        });
        return;
      }

      localStorage.setItem('ptit_cust_name', name);
      localStorage.setItem('ptit_cust_phone', phone);

      const history = JSON.parse(localStorage.getItem('ptit_order_history') || '[]');
      history.unshift(newOrder);
      localStorage.setItem('ptit_order_history', JSON.stringify(history));

      localStorage.removeItem('ptit_cart');

      if (payMethod === 'QR') {
        const qrUrl = `https://img.vietqr.io/image/MB-0944508758-compact2.png?amount=${grandTotal}&addInfo=${orderId}&accountName=Tran Cong Nguyen`;
        Swal.fire({
          icon: 'success',
          title: 'Đặt hàng thành công!',
          text: `Mã đơn: ${orderId}. Sếp bấm OK để quét mã VietQR nhé!`,
          confirmButtonColor: '#e11d48'
        }).then(() => window.open(qrUrl, '_blank'));
      } else {
        Swal.fire({
          icon: 'success',
          title: 'Đặt hàng thành công!',
          text: `Mã đơn: ${orderId}. Đơn đã bắn qua Admin, shipper sẽ liên hệ ngay!`,
          confirmButtonColor: '#e11d48'
        });
      }

      discountAmount = 0;
      updateUI();
      closeCartModal();
    }

    function openHistoryModal() {
      renderHistoryList();
      document.getElementById('history-modal').classList.remove('hidden');
    }

    function closeHistoryModal() {
      document.getElementById('history-modal').classList.add('hidden');
    }

    function renderHistoryList() {
      const container = document.getElementById('history-list');
      const localHistory = JSON.parse(localStorage.getItem('ptit_order_history') || '[]');

      if (localHistory.length === 0) {
        container.innerHTML = `<div class="text-center py-8 text-xs text-slate-400">Sếp chưa có đơn hàng nào</div>`;
        return;
      }

      fetch('orders.json?v=' + new Date().getTime())
        .then(res => res.json())
        .then(serverOrders => {
          const updatedHistory = localHistory.map(myOrder => {
            const match = serverOrders.find(so => so.id === myOrder.id);
            if (match && match.status) {
              myOrder.status = match.status;
            }
            return myOrder;
          });

          localStorage.setItem('ptit_order_history', JSON.stringify(updatedHistory));
          displayHistoryCards(container, updatedHistory);
        })
        .catch(err => {
          displayHistoryCards(container, localHistory);
        });
    }

    function displayHistoryCards(container, history) {
      container.innerHTML = history.map(order => {
        const currentStatus = order.status || 'Chờ xác nhận';

        let badgeStyle = 'bg-amber-500/10 text-amber-400 border-amber-500/20';
        let statusIcon = 'fa-hourglass-start';

        if (currentStatus === 'Đang đi lấy hàng') {
          badgeStyle = 'bg-sky-500/10 text-sky-400 border-sky-500/20';
          statusIcon = 'fa-person-biking';
        } else if (currentStatus === 'Đang giao') {
          badgeStyle = 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20';
          statusIcon = 'fa-rocket';
        } else if (currentStatus === 'Hoàn thành') {
          badgeStyle = 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20';
          statusIcon = 'fa-circle-check';
        } else if (currentStatus === 'Đã hủy') {
          badgeStyle = 'bg-rose-500/10 text-rose-400 border-rose-500/20';
          statusIcon = 'fa-circle-xmark';
        }

        return `
          <div class="bg-slate-950 border border-slate-800 rounded-2xl p-4 space-y-3">
            <div class="flex items-center justify-between border-b border-slate-800 pb-2">
              <div>
                <span class="font-extrabold text-xs text-rose-500">${order.id}</span>
                <p class="text-[10px] text-slate-500">${order.date}</p>
              </div>

              <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-full border ${badgeStyle} flex items-center gap-1.5">
                <i class="fa-solid ${statusIcon}"></i>
                <span>${currentStatus}</span>
              </span>
            </div>

            <div class="space-y-1">
              ${order.items.map(item => `
                <div class="flex justify-between text-xs">
                  <span class="text-slate-300">${item.title || item.name} <b class="text-white">x${item.qty}</b></span>
                  <span class="font-semibold text-slate-400">${(item.price * item.qty).toLocaleString('vi-VN')}đ</span>
                </div>
              `).join('')}
            </div>

            <div class="pt-2 border-t border-slate-800 text-xs text-slate-400 flex flex-col gap-1">
              <div class="flex justify-between items-center">
                <span>Giao tại: <b class="text-slate-200">${order.customer.location}</b></span>
                <span class="font-bold text-rose-500">Tổng: ${order.total.toLocaleString('vi-VN')}đ</span>
              </div>
              ${order.customer.deliveryTime ? `
                <div class="text-[11px] text-amber-400 flex items-center gap-1 pt-1">
                  <i class="fa-solid fa-clock"></i> <span>Thời gian giao: <b>${order.customer.deliveryTime}</b></span>
                </div>
              ` : ''}
            </div>
          </div>
        `;
      }).join('');
    }

    function clearHistory() {
      localStorage.removeItem('ptit_order_history');
      renderHistoryList();
    }
  </script>
</body>
</html>