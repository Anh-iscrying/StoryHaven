<?php
session_start(); // Khởi động session

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['user_id'])) {
  // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
  header("Location: ĐN.php");
  exit;
}

// Kết nối đến database
include 'db/connect.php';

// Lấy ID người dùng từ session
$userId = $_SESSION['user_id'];

// Xử lý form khi người dùng nhấn nút "Lưu"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate dữ liệu đầu vào (ví dụ đơn giản)
    $name = isset($_POST["name"]) ? trim($_POST["name"]) : '';
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : '';
    $phone = isset($_POST["phone"]) ? trim($_POST["phone"]) : '';
    $user = isset($_POST["user"]) ? trim($_POST["user"]) : '';
    $sex = isset($_POST["sex"]) ? trim($_POST["sex"]) : '';

    // Kiểm tra các trường bắt buộc
    if (empty($name) || empty($email) || empty($user)) {
        $response = ['status' => 'error', 'message' => 'Vui lòng nhập đầy đủ thông tin bắt buộc.'];
        echo json_encode($response);
        exit;
    }

  // Lấy dữ liệu từ form (Sử dụng prepared statement để chống SQL injection)
  $name = $mysqli->real_escape_string($_POST["name"]);
  $email = $mysqli->real_escape_string($_POST["email"]);
  $phone = $mysqli->real_escape_string($_POST["phone"]);
  $user = $mysqli->real_escape_string($_POST["user"]);
  $sex = $mysqli->real_escape_string($_POST["sex"]);

  // Cập nhật thông tin tài khoản trong database (Sử dụng prepared statement để chống SQL injection)
    $sql = "UPDATE tbl_user SET name = ?, email = ?, phone = ?, user = ?, sex = ? WHERE id = ?";
    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sssssi", $name, $email, $phone, $user, $sex, $userId);

        if ($stmt->execute()) {
            $response = ['status' => 'success', 'message' => 'Thông tin tài khoản đã được cập nhật thành công!'];
        } else {
            $response = ['status' => 'error', 'message' => 'Lỗi: ' . $stmt->error];
        }

        $stmt->close();
    } else {
        $response = ['status' => 'error', 'message' => 'Lỗi prepare: ' . $mysqli->error];
    }

    header('Content-Type: application/json');
    echo json_encode($response); // Trả về JSON
    exit;
}

// Truy vấn lấy thông tin tài khoản hiện tại
$sql = "SELECT * FROM tbl_user WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows == 1) {
  $row = $result->fetch_assoc();
  $name = htmlspecialchars($row["name"] ?? '');
  $email = htmlspecialchars($row["email"] ?? '');
  $phone = htmlspecialchars($row["phone"]);
  $user = htmlspecialchars($row["user"]);
  $sex = htmlspecialchars($row["sex"] ?? ''); //Thêm giới tính
} else {
  echo "Không tìm thấy tài khoản";
  exit;
}

$stmt->close();
// Đóng kết nối (Đóng sau khi sử dụng xong)
$mysqli->close();
?>