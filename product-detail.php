<!DOCTYPE html>
<html lang="vi" class="bg-slate-950">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chi Tiết Quán - PTITHCM Express</title>
  
  <!-- Tailwind CSS & FontAwesome CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
  
  <!-- SweetAlert2 (Dark Theme) & jQuery CDN -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" referrerpolicy="no-referrer"></script>
  <link href="//cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.js"></script>

  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
  </style>
</head>
<body class="bg-slate-950 text-slate-100 antialiased min-h-screen pb-28">

  <!-- Header Navigation -->
  <header class="sticky top-3 z-40 px-4 max-w-7xl mx-auto">
    <div class="bg-slate-900/90 backdrop-blur-md text-white rounded-2xl px-6 py-3.5 shadow-2xl border border-slate-800 flex items-center justify-between">
      <a href="index.php" class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-rose-600 flex items-center justify-center font-black text-white text-xl shadow-lg shadow-rose-600/40">P</div>
        <div>
          <span class="font-extrabold text-lg block leading-none">PTITHCM <span class="text-rose-500">EXPRESS</span></span>
          <span class="text-xs text-slate-400 font-medium">Trạm Ship Nội Khu 97 Man Thiện</span>
        </div>
      </a>
      <a href="index.php" class="bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs sm:text-sm font-semibold px-4 py-2.5 rounded-xl border border-slate-700 flex items-center gap-2 transition active:scale-95">
        <i class="fa-solid fa-arrow-left"></i> Quay lại thực đơn
      </a>
    </div>
  </header>

  <!-- Main Content Container -->
  <main class="max-w-4xl mx-auto px-4 pt-8 space-y-8">

    <!-- 1. THÔNG TIN QUÁN & CHỌN MÓN -->
    <div id="restaurant-detail-container" class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start bg-slate-900 border border-slate-800 p-6 sm:p-8 rounded-3xl shadow-2xl">
      <!-- JS Render động từ menu.json -->
    </div>

    <!-- 2. KHUNG ĐÁNH GIÁ CỦA KHÁCH HÀNG -->
    <section class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 space-y-6 shadow-2xl">
      <div class="flex items-center justify-between border-b border-slate-800 pb-4">
        <h2 class="text-xl font-extrabold text-white tracking-tight">Đánh giá của khách hàng</h2>
        <span class="text-xs text-slate-400 font-medium">Trải nghiệm thực tế</span>
      </div>

      <div id="reviews-list-container" class="space-y-4 max-h-96 overflow-y-auto pr-2">
        <!-- Đổ danh sách bình luận động -->
      </div>
    </section>

    <!-- 3. KHUNG GỬI ĐÁNH GIÁ MÓN ĂN -->
    <section class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 space-y-5 shadow-2xl">
      <div>
        <h2 class="text-xl font-extrabold text-white tracking-tight">Đánh giá món ăn</h2>
        <p class="text-xs text-slate-400 mt-1">Đánh giá của bạn (tối thiểu 10 ký tự)</p>
      </div>

      <!-- Chọn số sao -->
      <div class="flex items-center gap-2 text-2xl text-amber-400 cursor-pointer" id="star-rating-box">
        <i class="fa-solid fa-star star-item" onclick="selectRatingStar(1)"></i>
        <i class="fa-solid fa-star star-item" onclick="selectRatingStar(2)"></i>
        <i class="fa-solid fa-star star-item" onclick="selectRatingStar(3)"></i>
        <i class="fa-solid fa-star star-item" onclick="selectRatingStar(4)"></i>
        <i class="fa-solid fa-star star-item" onclick="selectRatingStar(5)"></i>
      </div>

      <!-- Ô nhập nội dung đánh giá -->
      <div>
        <textarea 
          id="review-text-input" 
          rows="4" 
          minlength="10"
          placeholder="Mô tả trải nghiệm của bạn (tối thiểu 10 ký tự)..." 
          class="w-full bg-slate-950 border border-slate-800 text-slate-100 rounded-2xl p-4 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-rose-500 placeholder-slate-500 transition"
        ></textarea>
      </div>

      <!-- Nút gửi đánh giá -->
      <button 
        onclick="submitCustomerReview()" 
        class="bg-orange-600 hover:bg-orange-500 text-white font-extrabold text-sm px-8 py-3 rounded-2xl shadow-lg shadow-orange-600/30 transition active:scale-95">
        Gửi đánh giá
      </button>
    </section>

  </main>

  <!-- JavaScript Scripting -->
  <script>
    let currentRestaurant = null;
    let selectedOption = null;
    let currentQty = 1;
    let userSelectedStars = 5;

    document.addEventListener("DOMContentLoaded", () => {
      const urlParams = new URLSearchParams(window.location.search);
      const restaurantId = urlParams.get('id');

      fetch('menu.json')
        .then(res => res.json())
        .then(data => {
          currentRestaurant = data.find(q => q.id === restaurantId) || data[0];
          renderDetail(currentRestaurant);
          loadAndRenderReviews(currentRestaurant.id);
        })
        .catch(err => console.error("Lỗi tải file menu.json:", err));
    });

    // 1. RENDER CHI TIẾT QUÁN & MÓN ĂN
    function renderDetail(restaurant) {
      selectedOption = restaurant.items[0];
      currentQty = 1;

      const container = document.getElementById('restaurant-detail-container');
      container.innerHTML = `
        <div class="h-72 sm:h-80 rounded-2xl overflow-hidden border border-slate-800 relative shadow-inner">
          <img src="${restaurant.image}" alt="${restaurant.name}" class="w-full h-full object-cover">
        </div>

        <div class="space-y-5">
          <div>
            <span class="text-[10px] font-bold tracking-wider text-rose-500 uppercase">${restaurant.tag || 'QUÁN HÓT'}</span>
            <h1 class="text-2xl sm:text-3xl font-black text-white mt-0.5">${restaurant.name}</h1>
            <p class="text-xs text-slate-400 mt-1 flex items-center gap-1.5">
              <i class="fa-solid fa-location-dot text-rose-500"></i> ${restaurant.address}
            </p>
            <div class="flex items-center gap-2 mt-2 text-xs text-amber-400 font-bold">
              <span>★ ${restaurant.rating ? restaurant.rating.toFixed(1) : '5.0'}</span>
              <span class="text-slate-500">(${restaurant.reviews_count || 0} reviews)</span>
            </div>
          </div>

          <p id="selected-price" class="text-3xl font-black text-rose-500">
            ${selectedOption.price.toLocaleString('vi-VN')} đ
          </p>

          <p class="text-xs text-slate-400 leading-relaxed">${restaurant.desc}</p>

          <div class="flex items-center gap-3 text-xs">
            <span class="bg-slate-800 text-slate-300 px-3 py-1.5 rounded-xl border border-slate-700 font-semibold">
              <i class="fa-solid fa-clock text-rose-500 mr-1"></i> ${restaurant.delivery_time || '18 phút'}
            </span>
          </div>

          <div class="space-y-2 pt-2">
            <label class="text-xs font-bold text-slate-300 block uppercase tracking-wider">Lựa chọn:</label>
            <div class="flex flex-wrap gap-2">
              ${restaurant.items.map((item, idx) => `
                <button onclick="selectItemOption(${idx})" id="opt-btn-${idx}" 
                  class="option-btn px-3.5 py-2 rounded-xl text-xs font-bold border transition ${idx === 0 ? 'bg-orange-600 text-white border-orange-500 shadow-md shadow-orange-600/30' : 'bg-slate-950 text-slate-300 border-slate-800 hover:border-slate-700'}">
                  ${item.name} (${item.price.toLocaleString('vi-VN')} đ)
                </button>
              `).join('')}
            </div>
          </div>

          <div class="flex items-center gap-4 pt-4 border-t border-slate-800">
            <div class="flex items-center gap-3 bg-slate-950 border border-slate-800 rounded-2xl p-1.5">
              <button onclick="changeQty(-1)" class="w-8 h-8 rounded-xl bg-slate-800 font-bold text-white hover:bg-slate-700 transition">-</button>
              <span id="qty-display" class="font-bold text-sm px-2">1</span>
              <button onclick="changeQty(1)" class="w-8 h-8 rounded-xl bg-slate-800 font-bold text-white hover:bg-slate-700 transition">+</button>
            </div>

            <button onclick="addToCartFromDetail()" class="flex-1 bg-orange-600 hover:bg-orange-500 text-white font-extrabold text-sm py-3.5 rounded-2xl shadow-lg shadow-orange-600/30 transition active:scale-95">
              Thêm vào giỏ
            </button>
          </div>
        </div>
      `;
    }

    function selectItemOption(index) {
      selectedOption = currentRestaurant.items[index];
      document.getElementById('selected-price').innerText = selectedOption.price.toLocaleString('vi-VN') + ' đ';

      document.querySelectorAll('.option-btn').forEach((btn, idx) => {
        if (idx === index) {
          btn.className = "option-btn px-3.5 py-2 rounded-xl text-xs font-bold border transition bg-orange-600 text-white border-orange-500 shadow-md shadow-orange-600/30";
        } else {
          btn.className = "option-btn px-3.5 py-2 rounded-xl text-xs font-bold border transition bg-slate-950 text-slate-300 border-slate-800 hover:border-slate-700";
        }
      });
    }

    function changeQty(delta) {
      currentQty += delta;
      if (currentQty < 1) currentQty = 1;
      document.getElementById('qty-display').innerText = currentQty;
    }

    function addToCartFromDetail() {
      let cart = JSON.parse(localStorage.getItem('ptit_cart') || '[]');
      const itemTitle = `${selectedOption.name} (${currentRestaurant.name})`;

      const existing = cart.find(c => c.title === itemTitle);
      if (existing) {
        existing.qty += currentQty;
      } else {
        cart.push({
          title: itemTitle,
          price: selectedOption.price,
          qty: currentQty
        });
      }

      localStorage.setItem('ptit_cart', JSON.stringify(cart));

      Swal.fire({
        icon: 'success',
        title: 'Đã thêm vào giỏ!',
        text: `Đã thêm ${currentQty}x ${itemTitle}`,
        confirmButtonColor: '#ea580c'
      }).then(() => {
        window.location.href = 'index.php';
      });
    }

    // 2. ĐÁNH GIÁ
    function loadAndRenderReviews(restaurantId) {
      fetch(`save_rate.php?id=${restaurantId}`)
        .then(res => res.json())
        .then(res => {
          const reviews = res.data || [];
          const container = document.getElementById('reviews-list-container');

          if (reviews.length === 0) {
            container.innerHTML = `<p class="text-xs text-slate-500 text-center py-6">Chưa có đánh giá nào cho quán này. Hãy là người đầu tiên đánh giá!</p>`;
            return;
          }

          container.innerHTML = reviews.map(rev => `
            <div class="bg-slate-950/80 border border-slate-800/80 rounded-2xl p-4 space-y-2">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center font-bold text-xs text-orange-400">
                    ${rev.name.charAt(0).toUpperCase()}
                  </div>
                  <div>
                    <h4 class="font-bold text-xs text-white">${rev.name}</h4>
                    <div class="text-amber-400 text-[10px] space-x-0.5">
                      ${'★'.repeat(rev.stars)}${'☆'.repeat(5 - rev.stars)}
                    </div>
                  </div>
                </div>
                <span class="text-[10px] text-slate-500">${rev.date}</span>
              </div>
              <p class="text-xs text-slate-300 leading-relaxed pl-11">${rev.comment}</p>
            </div>
          `).join('');
        })
        .catch(err => console.error("Lỗi đọc rate.json:", err));
    }

    // 3. XỬ LÝ CHỌN SAO & GỬI ĐÁNH GIÁ (GIỚI HẠN MIN 10 KÝ TỰ)
    function selectRatingStar(stars) {
      userSelectedStars = stars;
      const starIcons = document.querySelectorAll('.star-item');
      starIcons.forEach((s, idx) => {
        if (idx < stars) {
          s.classList.remove('fa-regular');
          s.classList.add('fa-solid');
        } else {
          s.classList.remove('fa-solid');
          s.classList.add('fa-regular');
        }
      });
    }

    function submitCustomerReview() {
      const commentInput = document.getElementById('review-text-input').value.trim();

      // Kiểm tra độ dài tối thiểu 10 ký tự
      if (commentInput.length < 10) {
        Swal.fire({
          icon: 'warning',
          title: 'Đánh giá quá ngắn',
          text: `Nội dung đánh giá phải có ít nhất 10 ký tự (Hiện tại: ${commentInput.length} ký tự)!`,
          confirmButtonColor: '#ea580c'
        });
        return;
      }

      const savedName = localStorage.getItem('ptit_cust_name') || 'Sinh viên PTIT';

      const payload = {
        restaurant_id: currentRestaurant.id,
        name: savedName,
        stars: userSelectedStars,
        comment: commentInput
      };

      fetch('save_rate.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      })
      .then(res => res.json())
      .then(res => {
        if (res.success) {
          document.getElementById('review-text-input').value = '';
          selectRatingStar(5);
          loadAndRenderReviews(currentRestaurant.id);

          Swal.fire({
            icon: 'success',
            title: 'Cảm ơn bạn!',
            text: 'Đánh giá thành công.',
            confirmButtonColor: '#ea580c'
          });
        }
      })
      .catch(err => {
        console.error("Lỗi ghi rate.json:", err);
        Swal.fire({
          icon: 'error',
          title: 'Lỗi',
          text: 'Không thể lưu đánh giá!',
          confirmButtonColor: '#ea580c'
        });
      });
    }
  </script>
</body>
</html>