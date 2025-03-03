document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const errorMessage = document.getElementById('errorMessage');

    loginForm.addEventListener('submit', async (event) => {
        event.preventDefault(); // Ngăn chặn submit mặc định

        const user = document.getElementById('user').value;
        const pass = document.getElementById('pass').value;

        const formData = new FormData();
        formData.append('user', user);
        formData.append('pass', pass);

        try {
            const response = await fetch('login.php', { 
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.status === 'success') {
                // Đăng nhập thành công
                if (data.role == 1) {
                    window.location.href = 'admin_users.php';
                } else {
                    window.location.href = 'Account.php';
                }
            } else {
                // Đăng nhập thất bại
                errorMessage.textContent = data.message; // Hiển thị thông báo lỗi
            }
        } catch (error) {
            console.error('Lỗi:', error);
            errorMessage.textContent = 'Có lỗi xảy ra trong quá trình đăng nhập.';
        }
    });
});