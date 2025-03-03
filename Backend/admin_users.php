<?php
  // Kết nối đến database
  include 'db/connect.php';

  // Kiểm tra kết nối
  if ($mysqli->connect_errno) {
    die("Kết nối database thất bại: " . $mysqli->connect_error);
  }

  // Lấy các tham số từ URL
  $searchTerm = isset($_GET['search']) ? $mysqli->real_escape_string($_GET['search']) : '';
  $statusFilter = isset($_GET['status']) ? $mysqli->real_escape_string($_GET['status']) : '';
  $sexFilter = isset($_GET['sex']) ? $mysqli->real_escape_string($_GET['sex']) : '';

  // Xây dựng câu truy vấn SQL
  $sql = "SELECT id, name, sex, email, phone, user, role, status FROM tbl_user WHERE 1=1"; // 1=1 để dễ dàng thêm điều kiện

  // Thêm điều kiện tìm kiếm
  if (!empty($searchTerm)) {
    $sql .= " AND (name LIKE '%$searchTerm%' OR email LIKE '%$searchTerm%' OR user LIKE '%$searchTerm%')";
  }

  // Thêm điều kiện lọc theo trạng thái
  if (!empty($statusFilter)) {
    if($statusFilter == 'active'){
      $sql .= " AND status = 'active'";
    }
    elseif($statusFilter == 'inactive'){
       $sql .= " AND status = 'inactive'";
    }
    else{
      $sql .= " AND status = 'blocked'";
    }

  }

  // Thêm điều kiện lọc theo giới tính
  if (!empty($sexFilter)) {
    $sql .= " AND sex = '$sexFilter'";
  }

  // Thực hiện truy vấn
  $result = $mysqli->query($sql);

  if ($result === false) {
    echo "Lỗi truy vấn: " . $mysqli->error . "<br>";
    exit;
  }

  // Hiển thị kết quả
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      echo "<tr>";
      echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
      echo "<td>" . htmlspecialchars($row["name"] ?? '') . "</td>";
      echo "<td>" . htmlspecialchars($row["sex"] ?? '') . "</td>";
      echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
      echo "<td>" . htmlspecialchars($row["phone"]) . "</td>";
      echo "<td>" . htmlspecialchars($row["user"]) . "</td>";
      echo "<td>" . htmlspecialchars($row["role"]) . "</td>";
      echo "<td>
               <button onclick=\"viewUser('" . htmlspecialchars($row["id"]) . "')\">Xem</button>
               <button onclick=\"toggleBlockUser('" . htmlspecialchars($row["id"]) . "')\">" . ($row["status"] == 'blocked' ? 'Mở' : 'Khóa') . "</button>
             </td>";
      echo "</tr>";
    }
  } else {
    echo "<tr><td colspan='8'>Không có người dùng nào phù hợp.</td></tr>";
  }

  // Đóng kết nối
  $mysqli->close();
?>