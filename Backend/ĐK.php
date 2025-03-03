<?php
// Kết nối đến cơ sở dữ liệu
include_once('db/connect.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Kiểm tra xem mật khẩu có khớp không
    if ($password !== $confirmPassword) {
        echo json_encode(['status' => 'error', 'message' => 'Mật khẩu và nhập lại mật khẩu không khớp!']);
        exit;
    }

    // Kiểm tra xem email đã tồn tại chưa
    $sql_check_email = "SELECT id FROM tbl_user WHERE email = ?";
    $stmt_check_email = $mysqli->prepare($sql_check_email);

    if (!$stmt_check_email) {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi prepare (check email): ' . $mysqli->error]);
        exit;
    }

    $stmt_check_email->bind_param("s", $email);
    $stmt_check_email->execute();
    $stmt_check_email->store_result();

    if ($stmt_check_email->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Địa chỉ email này đã được sử dụng.']);
        $stmt_check_email->close();
        exit;
    }

    $stmt_check_email->close();

    // Băm mật khẩu
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Email chưa tồn tại, tiến hành đăng ký
    $sql = "INSERT INTO tbl_user (name, email, phone, pass) VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi prepare (insert): ' . $mysqli->error]);
        exit;
    }

    $stmt->bind_param("ssss", $name, $email, $phone, $hashedPassword);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Đăng ký thành công!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Có lỗi xảy ra: ' . $stmt->error]);
    }
    $stmt->close();
    $mysqli->close();

} else {
    echo json_encode(['status' => 'error', 'message' => 'Phương thức không được hỗ trợ.']);
}
?>