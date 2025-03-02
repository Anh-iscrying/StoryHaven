<?php
// Kết nối đến cơ sở dữ liệu
include_once('db/connect.php');

// Kiểm tra xem bảng có tồn tại không (bạn nên giữ lại phần này)
$result = $mysqli->query("SHOW TABLES FROM webbansach LIKE 'tbl_user'");
if ($result->num_rows == 0) {
    // Bảng không tồn tại, tạo bảng
    $sql_create_table = "
        CREATE TABLE tbl_user (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            phone VARCHAR(20),
            pass VARCHAR(255) NOT NULL
        );
    ";
    if ($mysqli->query($sql_create_table) === TRUE) {
        echo "Bảng tbl_user đã được tạo thành công.";
    } else {
        die("Lỗi tạo bảng: " . $mysqli->error);
    }
}


if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Kiểm tra xem mật khẩu có khớp không
    if ($password === $confirmPassword) {

        // Kiểm tra xem email đã tồn tại chưa
        $sql_check_email = "SELECT id FROM tbl_user WHERE email = ?";
        $stmt_check_email = $mysqli->prepare($sql_check_email);
        $stmt_check_email->bind_param("s", $email);
        $stmt_check_email->execute();
        $stmt_check_email->store_result();

        if ($stmt_check_email->num_rows > 0) {
            echo "<script>alert('Địa chỉ email này đã được sử dụng. Vui lòng đăng nhập hoặc sử dụng địa chỉ email khác.'); window.location.href='ĐN.php';</script>";
            exit();
        } else {
            // Email chưa tồn tại, tiến hành đăng ký
            $sql = "INSERT INTO tbl_user (name, email, phone, pass) VALUES (?, ?, ?, ?)";
            $stmt = $mysqli->prepare($sql);

            if ($stmt) { // Kiểm tra xem prepare() có thành công không
                $stmt->bind_param("ssss", $name, $email, $phone, $password); // Lưu mật khẩu thẳng vào DB

                if ($stmt->execute()) {
                    // Đăng ký thành công, có thể chuyển hướng đến trang đăng nhập
                    header("Location: ĐN.php");
                    exit();
                } else {
                    // Thông báo lỗi
                    echo "Có lỗi xảy ra: " . $stmt->error;
                }
                $stmt->close();
            } else {
                die("Lỗi prepare: " . $mysqli->error); // In lỗi từ prepare()
            }
        }
        $stmt_check_email->close();

    } else {
        echo "<script>alert('Mật khẩu và nhập lại mật khẩu không khớp!');</script>";
    }
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng Kí</title>
    <link rel="stylesheet" href="css/Đk.css" type="text/css">
</head>
<body>
    <div class="gdđn">
        <div class="khunglon">
            <div class="x"><a href="ĐN.html">X</a></div>
            <div class="khungnho">
                <div class="khungbe">
                    <div class="thamgiagt">
                        <div class="dnvao">
                            <span class="chu">Tham gia với chúng tôi</span>
                        </div>
                        <div class="dnvao">
                            <span class="quyenloi">Là một phần của cộng đồng tác giả và độc giả toàn cầu, mọi người đều được kết nối bằng sức mạnh của trí tưởng tượng.</span>
                        </div>
                    </div>
                    
                    <form id="registerForm" method="post" onsubmit="return validateForm()">
                        <input type="text" id="name" name="name" placeholder="Tên" required>
                        <input type="email" id="email" name="email" placeholder="Email" required>
                        <div class="error" id="email-error" style="display: none; color: red;">Email không hợp lệ.</div>
                        <input type="tel" id="phone" name="phone" placeholder="Số điện thoại" required>
                        <input type="password" id="password" name="password" placeholder="Mật khẩu" required>
                        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Nhập lại mật khẩu" required>
                        <button type="submit" name="register" class="buttondn">Đăng ký</button>
                    </form>
                </div>
                <footer class="khungdk">
                <span>Nếu bạn đã có tài khoản <button class="dangki"><a class="dangki" href="ĐN.php">Đăng nhập</a></button></span>
                </footer>
                <div class="quenMK">
                    By continuing, you agree to Website's <a class="blue" href="">Điều khoản Dịch vụ</a> and <a class="blue" href="">Chính Sách Bảo Mật.</a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function validateForm() {
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirmPassword').value;
            
            if (password !== confirmPassword) {
                alert("Mật khẩu và nhập lại mật khẩu không khớp!");
                return false;
            }
         // Kiểm tra email
        const emailInput = document.getElementById('email');
        const emailError = document.getElementById('email-error');
        const emailValue = emailInput.value;
        const emailRegex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;
        if (!emailRegex.test(emailValue)) {
            emailError.style.display = 'block';
            return false;
        } else {
            emailError.style.display = 'none';
        }

        return true;
        }
    </script>
</body>
</html>