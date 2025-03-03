<?php
  ob_end_clean(); 
  header('Content-Type: application/json'); 

  error_log("toggle_block_user.php called"); 
  // Kết nối database
  include 'db/connect.php';

  // Kiểm tra kết nối
  if ($mysqli->connect_errno) {
    $error_message = "Kết nối database thất bại: " . $mysqli->connect_error;
    error_log($error_message); // Log lỗi kết nối
    echo json_encode(['success' => false, 'message' => $error_message]);
    exit();
  }

  // Lấy ID người dùng từ tham số GET
  $userId = $_GET['id'] ?? null;
  error_log("User ID: " . $userId); // Log user ID

  if ($userId === null || !is_numeric($userId)) {
    $error_message = "ID người dùng không hợp lệ.";
    error_log($error_message); // Log lỗi ID
    echo json_encode(['success' => false, 'message' => $error_message]);
    exit();
  }

  // Lấy trạng thái hiện tại của người dùng
  $sql_select = "SELECT status FROM tbl_user WHERE id = ?";
  $stmt_select = $mysqli->prepare($sql_select);
  if (!$stmt_select) {
    $error_message = "Lỗi prepare select: " . $mysqli->error;
    error_log($error_message); // Log lỗi prepare
    echo json_encode(['success' => false, 'message' => $error_message]);
    exit();
  }
  $stmt_select->bind_param("i", $userId);
  if (!$stmt_select->execute()) {
    $error_message = "Lỗi execute select: " . $stmt_select->error;
    error_log($error_message); // Log lỗi execute
    echo json_encode(['success' => false, 'message' => $error_message]);
    exit();
  }
  $result_select = $stmt_select->get_result();

  if ($result_select->num_rows === 0) {
    $error_message = "Không tìm thấy người dùng với ID này.";
    error_log($error_message); // Log lỗi không tìm thấy
    echo json_encode(['success' => false, 'message' => $error_message]);
    exit();
  }

  $row = $result_select->fetch_assoc();
  $currentStatus = $row['status']; 

  // Xác định trạng thái mới
  $newStatus = ($currentStatus == 'active' || $currentStatus == null || $currentStatus == 'inactive') ? 'blocked' : 'active'; // Khóa nếu đang active hoặc inactive, mở nếu đang blocked. Thêm inactive và null để xử lý các trường hợp mới

  // Cập nhật trạng thái người dùng trong database
  $sql_update = "UPDATE tbl_user SET status = ? WHERE id = ?";
  $stmt_update = $mysqli->prepare($sql_update);
  if (!$stmt_update) {
    $error_message = "Lỗi prepare update: " . $mysqli->error;
    error_log($error_message); // Log lỗi prepare
    echo json_encode(['success' => false, 'message' => $error_message]);
    exit();
  }
  $stmt_update->bind_param("si", $newStatus, $userId);

  if ($stmt_update->execute()) {
    $message = "Tài khoản đã được " . ($newStatus == 'blocked' ? 'khóa' : 'mở khóa') . ".";
    error_log($message); // Log thông báo thành công
    echo json_encode(['success' => true, 'message' => $message, 'newStatus' => $newStatus]);
  } else {
    $error_message = "Lỗi cập nhật database: " . $stmt_update->error;
    error_log($error_message); // Log lỗi database
    echo json_encode(['success' => false, 'message' => $error_message]);
  }

  // Đóng kết nối
  $stmt_select->close();
  $stmt_update->close();
  $mysqli->close();
?>