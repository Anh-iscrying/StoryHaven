<?php
session_start(); // Khởi động session

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['user_id'])) {
  // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
  header("Location: login.php");
  exit;
}

// Kết nối đến database
include 'db/connect.php';

// Lấy ID người dùng từ session
$userId = $_SESSION['user_id'];

// Xử lý form khi người dùng nhấn nút "Lưu"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Lấy dữ liệu từ form
  $name = $mysqli->real_escape_string($_POST["name"]);
  $email = $mysqli->real_escape_string($_POST["email"]);
  $phone = $mysqli->real_escape_string($_POST["phone"]);
  $user = $mysqli->real_escape_string($_POST["user"]);

  // Cập nhật thông tin tài khoản trong database
  $sql = "UPDATE tbl_user SET name = '$name', email = '$email', phone = '$phone', user = '$user' WHERE id = '$userId'";

  if ($mysqli->query($sql) === TRUE) {
    $successMessage = "Thông tin tài khoản đã được cập nhật thành công!";
  } else {
    $errorMessage = "Lỗi: " . $sql . "<br>" . $mysqli->error;
  }
}

// Truy vấn lấy thông tin tài khoản hiện tại
$sql = "SELECT * FROM tbl_user WHERE id = '$userId'";
$result = $mysqli->query($sql);

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

// Đóng kết nối
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tài khoản - Wattpad</title>
    <link rel="stylesheet" href="/PTTKPM/css/Account.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container">
        <header class="header">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#"><img src="images/Book Haven (2).png" alt="Story Haven"></a>
                    <div class="d-flex align-items-center">
                        <a href="#" class="nav-link"><i class="fas fa-search"></i> Tìm kiếm</a>
                    </div>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownCategory" role="button" data-bs-toggle="dropdown" aria-expanded="false">Thể loại</a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownCategory">
                                    <li><a class="dropdown-item" href="#">Action</a></li>
                                    <li><a class="dropdown-item" href="#">Comedy</a></li>
                                    <li><a class="dropdown-item" href="#">Drama</a></li>
                                </ul>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="#">Thư viện</a></li>
                        </ul>
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownWrite" role="button" data-bs-toggle="dropdown" aria-expanded="false">Viết</a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownWrite">
                                <li><a class="dropdown-item" href="#">Viết một truyện mới</a></li>
                                <li><a class="dropdown-item" href="#">Truyện của tôi</a></li>
                            </ul>
                        </div>
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownAccount" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="images/schwi.png" alt="Avatar" class="rounded-circle" style="width: 30px; height: 30px; margin-right: 5px;">
                                <?php echo htmlspecialchars($name); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownAccount">
                                <li><a class="dropdown-item" href="#">Hồ sơ của tôi</a></li>
                                <li><a class="dropdown-item" href="#">Hộp thư</a></li>
                                <li><a class="dropdown-item" href="#">Thông báo</a></li>
                                <li><a class="dropdown-item" href="#">Thư viện</a></li>
                                <li><a class="dropdown-item" href="#">Ngôn ngữ: Tiếng Việt</a></li>
                                <li><a class="dropdown-item" href="#">Trợ giúp</a></li>
                                <li><a class="dropdown-item" href="#">Cài đặt</a></li>
                                <li><a class="dropdown-item" href="ĐN.php">Đăng xuất</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Phần hiển thị thông báo -->
        <?php if (isset($successMessage)): ?>
          <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <?php if (isset($errorMessage)): ?>
          <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
        <?php endif; ?>
    

        <section class="profile">
            <div class="profile__background"></div>
            <div class="profile__avatar"><img src="images/schwi.png" alt="Avatar của <?php echo htmlspecialchars($name); ?>"></div>
            <div class="profile__info">
                <h2 class="profile__name"><?php echo htmlspecialchars($name); ?></h2>
                <p class="profile__username">@<?php echo htmlspecialchars($user); ?></p>
                <div class="profile__stats">
                    <span><i class="fa fa-book"></i> 0 Tác phẩm</span>
                    <span><i class="fa fa-user-friends"></i> 1 Người theo dõi</span>
                </div>
            </div>
        </section>

        <nav class="tabs">
            <button class="tab tab--active" data-tab="gioithieu"><i class="fa fa-info-circle"></i> Giới thiệu</button>
            <button class="tab" data-tab="hoithoai"><i class="fa fa-comments"></i> Hội thoại</button>
            <button class="tab" data-tab="caidat"><i class="fas fa-cog"></i> Cài đặt</button> <!-- Thêm tab "Cài đặt" -->
        </nav>

        <main class="content">
            <section id="gioithieu" class="content__section content__section--active">
                <div class="container2">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <h3><i class="fa fa-users"></i> Thành viên</h3>
                                <p>Mai Phương Anh<br>Tào Thanh Hà</p>
                                <hr>
                                <h3><i class="fa fa-heart"></i> Đang theo dõi</h3>
                                <div class="following-list">
                                    <a href="Admin_Account.html" class="following-item"><img src="images/nền.jpg" alt="Avatar Admin"></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h3>Truyện của <?php echo htmlspecialchars($name); ?> <i class="fas fa-cog float-end"></i></h3>
                                    <p class="text-muted">0 Truyện đã Đăng • 3 Bản thảo (chỉ hiển thị với bạn)</p>
                                </div>
                                <div class="work-list">
                                    <div class="work-item">
                                        <img src="img/BanThao.png" alt="Bìa Khế ước máu">
                                        <div class="work-info">
                                            <h4>Khế ước máu</h4>
                                            <p><i class="fas fa-eye"></i> 62 <i class="fas fa-star"></i> 0 <i class="fas fa-list"></i> 2</p>
                                            <p class="work-summary">Này cô gái, đừng khóc nữa...</p>
                                            <div class="work-tags">
                                                <span class="badge bg-secondary">ngôn</span>
                                                <span class="badge bg-secondary">drama</span>
                                                <span class="badge bg-secondary">action</span>
                                                <span class="badge bg-secondary">+5 tag khác</span>
                                            </div>
                                            <p class="text-muted">BẢN THẢO (chỉ hiển thị với bạn)</p>
                                        </div>
                                    </div>
                                    <div class="work-item">
                                        <img src="img/TP1.jpg" alt="Bìa Trùng Sinh Vả Mặt">
                                        <div class="work-info">
                                            <h4>Trùng Sinh Vả Mặt</h4>
                                            <p><i class="fas fa-eye"></i> 123 <i class="fas fa-star"></i> 5 <i class="fas fa-list"></i> 10</p>
                                            <p class="work-summary">Một câu chuyện về sự báo thù và tình yêu...</p>
                                            <div class="work-tags">
                                                <span class="badge bg-secondary">trùng sinh</span>
                                                <span class="badge bg-secondary">ngôn tình</span>
                                                <span class="badge bg-secondary">hài hước</span>
                                                <span class="badge bg-secondary">+3 tag khác</span>
                                            </div>
                                            <p class="text-muted">BẢN THẢO (chỉ hiển thị với bạn)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="hoithoai" class="content__section">
                <div class="card">
                    <div class="title-cmt">Bình luận</div>
                    <div class="cmt">
                        <div class="box-cmt">
                            <form id="commentForm" class="row">
                                <div class="col-10">
                                    <textarea id="commentInput" placeholder="Nhập bình luận của bạn..." required></textarea>
                                </div>
                                <div class="col-2">
                                    <button type="submit"><i class="fas fa-paper-plane"></i></button>
                                </div>
                            </form>
                            <div id="commentsList">
                                <h3>Các bình luận:</h3>
                                <ul id="comments">
                                    <!-- Các bình luận sẽ được thêm vào đây bằng JavaScript -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Phần form chỉnh sửa thông tin tài khoản -->
            <section id="caidat" class="content__section">
                <div class="card">
                    <h3>Chỉnh sửa thông tin cá nhân</h3>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên:</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Điện thoại:</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $phone; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="user" class="form-label">Tên đăng nhập:</label>
                            <input type="text" class="form-control" id="user" name="user" value="<?php echo $user; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="sex" class="form-label">Giới tính:</label>
                             <select class="form-select" id="sex" name="sex">
                                <option value="male" <?php if($sex == 'male') echo 'selected'; ?>>Nam</option>
                                <option value="female" <?php if($sex == 'female') echo 'selected'; ?>>Nữ</option>
                                <option value="other" <?php if($sex == 'other') echo 'selected'; ?>>Khác</option>
                              </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </form>
                </div>
            </section>
        </main>
    </div>

    <script>
      const tabs = document.querySelectorAll('.tab');
      const sections = document.querySelectorAll('.content__section');
      const commentForm = document.getElementById('commentForm');
      const commentInput = document.getElementById('commentInput');
      const commentsList = document.getElementById('comments');
      const usernames = ["Alice", "Bob", "Charlie", "David", "Eve", "Schwi", "Shiro", "Izuna"];
      const avatars = ["images/avt1.jpg", "images/avt2.jpg", "images/avt3.jpg", "images/schwi.png", "images/nền.jpg", "images/avt10.jpg"];

        function generateId() {
            return Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
        }

        function getRandomUsername() {
            return usernames[Math.floor(Math.random() * usernames.length)];
        }

        function getRandomAvatar() {
            return avatars[Math.floor(Math.random() * avatars.length)];
        }

        function createCommentElement(commentText) {
            const commentId = generateId();
            const comment = document.createElement("li");
            comment.classList.add("comment");
            comment.dataset.commentId = commentId;
            comment.innerHTML = `
                <div class="comments-detail">
                    <div class="avt">
                        <img src="${getRandomAvatar()}" alt="Avatar">
                        <div class="nick-name">${getRandomUsername()}</div>
                    </div>
                    <div class="comments-info">${commentText}</div>
                    <div class="comments-item">
                        <span class="comment-date">${new Date().toLocaleString()}</span>
                        <div class="comments-symbol">
                            <div class="reply-btn"><i class="fas fa-reply"></i> (<span class="reply-count">0</span>)</div>
                            <div class="delete-btn" style="color: red; cursor: pointer;"><i class="fas fa-trash"></i></div>
                        </div>
                    </div>
                    <div class="replies" style="display: none;"></div>
                    <div class="reply-form" style="display: none;">
                        <form class="reply-form-inner row">
                            <div class="col-10"><textarea type="text" class="reply-input" placeholder="Nhập phản hồi của bạn..." required></textarea></div>
                            <div class="col-2"><button type="submit" class="send-reply"><i class="fas fa-paper-plane"></i></button></div>
                        </form>
                    </div>
                </div>
            `;

            const replyBtn = comment.querySelector(".reply-btn");
            const replyCount = comment.querySelector(".reply-count");
            const repliesContainer = comment.querySelector(".replies");
            const replyForm = comment.querySelector(".reply-form");
            const replyInput = replyForm.querySelector(".reply-input");
            const deleteBtn = comment.querySelector(".delete-btn");

            deleteBtn.addEventListener("click", function() {
                if (confirm("Bạn có chắc chắn muốn xóa bình luận này không?")) {
                    comment.remove();
                }
            });

            replyBtn.addEventListener("click", function() {
                replyForm.style.display = replyForm.style.display === "none" ? "block" : "none";
            });

            replyForm.addEventListener("submit", function(event) {
                event.preventDefault();
                const replyText = replyInput.value.trim();
                if (replyText === "") return;
                const replyToReply = createReplyElement(replyText);
                repliesContainer.appendChild(replyToReply);
                replyForm.style.display = "none";
                replyCount.textContent = parseInt(replyCount.textContent) + 1;
                repliesContainer.style.display = "block";
                replyInput.value = "";
            });
            return comment;
        }

        function createReplyElement(replyText) {
            const reply = document.createElement("div");
            reply.classList.add("comment", "reply");
            reply.innerHTML = `
                <div class="comments-detail">
                    <div class="avt">
                        <img src="${getRandomAvatar()}" alt="Avatar">
                        <div class="nick-name">${getRandomUsername()}</div>
                    </div>
                    <div class="comments-info">${replyText}</div>
                    <div class="comments-item">
                        <span class="comment-date">${new Date().toLocaleString()}</span>
                        <div class="comments-symbol">
                            <div class="reply-btn"><i class="fas fa-reply"></i> (<span class="reply-count">0</span>)</div>
                            <div class="delete-btn" style="color: red; cursor: pointer;"><i class="fas fa-trash"></i></div>
                        </div>
                    </div>
                    <div class="replies" style="display: none;"></div>
                    <div class="reply-form" style="display: none;">
                        <form class="reply-form-inner row">
                            <div class="col-10"><textarea type="text" class="reply-input" placeholder="Nhập phản hồi của bạn..." required></textarea></div>
                            <div class="col-2"><button type="submit" class="send-reply"><i class="fas fa-paper-plane"></i></button></div>
                        </form>
                    </div>
                </div>
            `;
            const replyBtn = reply.querySelector(".reply-btn");
            const replyCount = reply.querySelector(".reply-count");
            const repliesContainer = reply.querySelector(".replies");
            const replyForm = reply.querySelector(".reply-form");
            const replyInput = replyForm.querySelector(".reply-input");
            const deleteBtn = reply.querySelector(".delete-btn");

            deleteBtn.addEventListener("click", function() {
                if (confirm("Bạn có chắc chắn muốn xóa bình luận này không?")) {
                    reply.remove();
                }
            });

            replyBtn.addEventListener("click", function() {
                replyForm.style.display = replyForm.style.display === "none" ? "block" : "none";
            });

            replyForm.addEventListener("submit", function(event) {
                event.preventDefault();
                const replyText = replyInput.value.trim();
                if (replyText === "") return;
                const replyToReply = createReplyElement(replyText);
                repliesContainer.appendChild(replyToReply);
                replyForm.style.display = "none";
                replyCount.textContent = parseInt(replyCount.textContent) + 1;
                repliesContainer.style.display = "block";
                replyInput.value = "";
            });
            return reply;
        }

        commentForm.addEventListener("submit", function(event) {
            event.preventDefault();
            const commentText = commentInput.value.trim();
            if (commentText === "") return;
            const commentElement = createCommentElement(commentText);
            commentsList.appendChild(commentElement);
            commentInput.value = "";
        });

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('tab--active'));
                sections.forEach(s => s.classList.remove('content__section--active'));
                tab.classList.add('tab--active');
                const tabId = tab.dataset.tab;
                document.getElementById(tabId).classList.add('content__section--active');
            });
        });
    </script>
</body>
</html>