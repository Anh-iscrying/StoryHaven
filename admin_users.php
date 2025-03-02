<!DOCTYPE html>
<html>
<head>
  <title>Admin - Quản lý tài khoản người dùng</title>
  <link rel="stylesheet" href="/PTTKPM/css/adus.css">
  
</head>
<body>
  <div class="container">
    <header>
      <!-- Logo, Thông tin admin, Menu -->
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
            //echo "SQL: " . $sql . "<br>"; // In truy vấn SQL (chỉ dùng để debug)

            $result = $mysqli->query($sql);

            if ($result === false) {
              echo "Lỗi truy vấn: " . $mysqli->error . "<br>";
              exit;
            }

            if ($result->num_rows > 0) {
              // Lặp qua từng dòng dữ liệu và hiển thị
              while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["name"] ?? '') . "</td>"; // Xử lý NULL
                echo "<td>" . htmlspecialchars($row["sex"] ?? '') . "</td>";  // Xử lý NULL
                echo "<td>" . htmlspecialchars($row["email"] ?? '') . "</td>"; // Xử lý NULL
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
      <!-- Bản quyền, Thông tin footer -->
    </footer>
  </div>

  <script>
    // Các hàm Javascript (viewUser, editUser, toggleBlockUser, searchAndFilter)
    // Sẽ được điều chỉnh để gọi các file PHP xử lý thao tác (sử dụng AJAX)

    function viewUser(userId) {
        window.location.href = `Account.php?id=${userId}`;
    }

    function toggleBlockUser(userId) {
      alert(`Khóa/Mở khóa tài khoản của người dùng có ID ${userId}`);
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