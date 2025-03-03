<?php
session_start();
include_once('db/connect.php');

header('Content-Type: application/json'); // Trả về dữ liệu JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user'];
    $pass = $_POST['pass'];

    $sql = "SELECT id, pass, role, status FROM tbl_user WHERE email = ? OR user = ?";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi prepare: ' . $mysqli->error]);
        exit;
    }

    $stmt->bind_param("ss", $user, $user);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $storedPassword, $role, $status);
        $stmt->fetch();

        if ($status == 'blocked') {
            echo json_encode(['status' => 'error', 'message' => 'Tài khoản của bạn đã bị khóa.']);
        } else {
            if ($pass === $storedPassword) {
                $_SESSION['user_id'] = $id;
                echo json_encode(['status' => 'success', 'role' => $role]); // Trả về role
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Mật khẩu không chính xác.']);
            }
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Người dùng không tồn tại.']);
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Phương thức không được hỗ trợ.']);
}
?>