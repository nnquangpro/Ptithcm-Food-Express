# 📦 Micro-ECommerce & Order Dispatch System
> **Vietnamese:** Hệ thống quản lý đặt hàng & đánh giá sản phẩm nhẹ, tối ưu hiệu năng cao với cơ chế khóa file chống xung đột dữ liệu và tự động thông báo đơn hàng qua Discord Webhook.  
> **English:** A high-performance, lightweight order management & product rating system featuring file-locking concurrency control and automated Discord Webhook order notifications.

---

## 🌐 Language / Ngôn ngữ
- [Tiếng Việt](#-tiếng-việt)
- [English](#-english)

---

<a name="-tiếng-việt"></a>
## 🇻🇳 Tiếng Việt

### 🌟 Tính năng nổi bật
- **Giao diện đặt hàng & Chi tiết sản phẩm:** Xem danh sách thực đơn (`menu.json`), chi tiết món (`product-detail.php`), và đặt hàng mượt mà.
- **Xử lý đơn hàng & Bất đồng bộ (Race Condition):** Sử dụng cơ chế khóa file (`LOCK_EX`) trong PHP khi xử lý gửi đơn (`send.php`) và ghi dữ liệu vào `orders.json` an toàn khi có nhiều truy cập cùng lúc.
- **Quản trị Admin toàn diện:** Trang `admin.php` hỗ trợ đăng nhập bảo mật (`login.php`), cập nhật trạng thái đơn hàng (`update_status.php`), xóa đơn (`delete_order.php`) và chỉnh sửa thực đơn (`update_menu.php`).
- **Tích hợp Tương tác & Đánh giá:** Hỗ trợ tính năng Chat/Gửi phản hồi (`chat.php`, `chat.json`) và Đánh giá sản phẩm (`save_rate.php`, `rate.json`).
- **Thông báo tự động:** Tích hợp Discord Webhook để bắn thông báo đơn hàng mới theo thời gian thực.
- **Kiến trúc tách biệt (Decoupled):** Dễ dàng deploy phần Static Frontend lên Vercel Edge Network và proxy API request về Backend PHP trên Server/Hosting.

### 🛠️ Cấu trúc File (Project Structure)
- `index.php`: Trang chủ hiển thị thực đơn và form đặt hàng.
- `product-detail.php`: Trang chi tiết sản phẩm.
- `send.php`: API xử lý đặt hàng, kiểm tra Rate-limit, khóa file `LOCK_EX` và gửi Discord Webhook.
- `admin.php`, `login.php`: Dashboard quản trị & Authentication.
- `update_status.php`, `delete_order.php`, `update_menu.php`: Các API quản trị dữ liệu đơn hàng và thực đơn.
- `save_rate.php`, `chat.php`: API xử lý đánh giá sao và tin nhắn phản hồi.
- `*.json`: Cơ sở dữ liệu dạng Flat-file JSON (`orders.json`, `menu.json`, `chat.json`, `rate.json`).

### 📐 Kiến trúc & Luồng xử lý (Architecture & Flow)
```text
[Client / Browser] ---> (Fetch POST /send.php)
                             |
                             v
                   [PHP Micro-Backend]
                      |           |
        (LOCK_EX)     v           v      (HTTP POST)
   [orders.json Database]     [Discord Webhook Notification]
