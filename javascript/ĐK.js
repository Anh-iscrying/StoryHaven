// register.js

document.addEventListener('DOMContentLoaded', function() { 
    const registerForm = document.getElementById('registerForm');
    const emailError = document.getElementById('email-error');

    registerForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const phone = document.getElementById('phone').value;
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirmPassword').value;

        // Validate email
        const emailRegex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;
        if (!emailRegex.test(email)) {
            emailError.style.display = 'block';
            return;
        } else {
            emailError.style.display = 'none';
        }

        if (password !== confirmPassword) {
            alert("Mật khẩu và nhập lại mật khẩu không khớp!");
            return;
        }

        const formData = new FormData();
        formData.append('name', name);
        formData.append('email', email);
        formData.append('phone', phone);
        formData.append('password', password);
        formData.append('confirmPassword', confirmPassword);

        try {
            const response = await fetch('register.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.status === 'success') {
                alert(data.message);
                window.location.href = 'ĐN.html'; // Chuyển đến trang đăng nhập
            } else {
                alert(data.message); // Hiển thị thông báo lỗi
            }
        } catch (error) {
            console.error('Lỗi:', error);
            alert('Có lỗi xảy ra trong quá trình đăng ký.');
        }
    });
});