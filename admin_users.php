<?php
session_start(); // **QUAN TRỌNG: Thêm dòng này vào đầu file!**
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin - Quản lý tài khoản người dùng</title>
  <link rel="stylesheet" href="css/adus.css">
  
</head>
<body>
  <div class="container">
    <header>
        <div>
        
        <img src="images/bookhaven.jpg" alt="Logo" height="50">
      </div>
    </header>

    <aside>
      <!-- Menu điều hướng bên trái -->
    </aside>

    <main>
      <h1>Quản lý tài khoản người dùng</h1>

      <div class="search-filter">
        <input type="text" id="search-input" placeholder="Tìm kiếm...">
        <select id="status-filter">
          <option value="">Tất cả trạng thái</option>
          <option value="active">Đã kích hoạt</option>
          <option value="inactive">Chưa kích hoạt</option>
          <option value="blocked">Bị khóa</option>
        </select>
        <select id="sex-filter">
          <option value="">Tất cả giới tính</option>
          <option value="male">Nam</option>
          <option value="female">Nữ</option>
          <option value="other">Khác</option>
        </select>
        <button onclick="searchAndFilter()">Tìm kiếm</button>
      </div>

      <table id="user-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Giới tính</th>
            <th>Email</th>
            <th>Điện thoại</th>
            <th>Tên đăng nhập</th>
            <th>Vai trò</th>
            <th>Thao tác</th>
          </tr>
        </thead>
        <tbody>
          <?php
            // Kết nối đến database
            include 'db/connect.php';

            // Kiểm tra kết nối
            if ($mysqli->connect_errno) {
              die("Kết nối database thất bại: " . $mysqli->connect_error);
            }

            // Truy vấn lấy dữ liệu người dùng
            $sql = "SELECT id, name, sex, email, phone, user, role FROM tbl_user";
           

            $result = $mysqli->query($sql);

            if ($result === false) {
              echo "Lỗi truy vấn: " . $mysqli->error . "<br>";
              exit;
            }

            if ($result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["name"] ?? '') . "</td>"; 
                echo "<td>" . htmlspecialchars($row["sex"] ?? '') . "</td>";  
                echo "<td>" . htmlspecialchars($row["email"] ?? '') . "</td>"; 
                echo "<td>" . htmlspecialchars($row["phone"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["user"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["role"]) . "</td>";
                echo "<td>
                         <button onclick=\"viewUser('" . htmlspecialchars($row["id"]) . "')\">Xem</button>
                         <button onclick=\"toggleBlockUser('" . htmlspecialchars($row["id"]) . "')\">Khóa/Mở</button>
                       </td>";
                echo "</tr>";
              }
            } else {
              echo "<tr><td colspan='8'>Không có người dùng nào.</td></tr>";
            }

            // Đóng kết nối
            $mysqli->close();
          ?>
        </tbody>
      </table>

      <div class="pagination">
        <!-- Phân trang -->
      </div>
    </main>

    <footer>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="ĐN.php" class="logout-button">Đăng xuất</a>
      <?php endif; ?>
    </footer>
  </div>

  <script>
  
    function viewUser(userId) {
        window.location.href = `Account.php?id=${userId}`;
    }

    function toggleBlockUser(userId) {
      fetch(`toggle_block_user.php?id=${userId}`) 
        .then(response => response.json()) 
        .then(data => {
          if (data.success) {
            // Cập nhật giao diện người dùng sau khi thành công
            alert(data.message); // Hiển thị thông báo thành công

            // Tải lại bảng người dùng để hiển thị trạng thái mới (tùy chọn)
            searchAndFilter();  

          } else {
            alert(data.message); // Hiển thị thông báo lỗi
          }
        })
        .catch(error => {
          console.error('Lỗi:', error);
          alert('Có lỗi xảy ra khi thực hiện thao tác.'); // Thông báo lỗi chung
        });
    }

    function searchAndFilter() {
      // Lấy giá trị từ input và select
      const searchTerm = document.getElementById('search-input').value.toLowerCase();
      const statusFilter = document.getElementById('status-filter').value;
      const sexFilter = document.getElementById('sex-filter').value;

      // Tạo URL để gửi yêu cầu AJAX
      const url = `search_users.php?search=${searchTerm}&status=${statusFilter}&sex=${sexFilter}`;

      // Sử dụng fetch API để gửi yêu cầu AJAX
      fetch(url)
        .then(response => response.text())
        .then(data => {
          // Thay thế nội dung của tbody bằng kết quả trả về
          document.querySelector('#user-table tbody').innerHTML = data;
        })
        .catch(error => {
          console.error('Lỗi:', error);
        });
    }
  </script>
</body>
</html>