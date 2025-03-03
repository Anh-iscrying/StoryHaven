// Hàm thêm bình luận mới
function addComment(text, nickname) {
    const commentsList = document.getElementById('comments');
    const newComment = document.createElement('li');
    newComment.classList.add('comment');
  
    newComment.innerHTML = `
        <div class="avt"><img src="img/avt.jpg" alt="Avatar"></div>
        <div class="comments-detail">
            <div class="nick-name">${nickname}</div>
            <div class="comments-info">${text}</div>
            <div class="comments-item">
                <div class="comment-date">${new Date().toLocaleDateString()}</div>
                <div class="comments-symbol">
                    <div><i class="fas fa-thumbs-up"></i></div>
                    <div><i class="fas fa-comment" onclick="toggleReply(this)"> Trả lời</i></div>
                </div>
            </div>
            <div class="reply-form" style="display: none;">
                <input type="text" class="reply-input" placeholder="Nhập câu trả lời...">
                <button class="reply-button">Gửi</button>
            </div>
        </div>
    `;
  
    // Thêm bình luận mới vào đầu danh sách
    commentsList.insertBefore(newComment, commentsList.firstChild);
  }
  
  // Hàm hiển thị/ẩn form trả lời
  function toggleReply(element) {
    const replyForm = element.closest('.comments-detail').querySelector('.reply-form');
    replyForm.style.display = replyForm.style.display === 'none' ? 'block' : 'none';
  }
  
  // Lắng nghe sự kiện submit form bình luận
  document.getElementById('commentForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const commentInput = document.getElementById('commentInput');
    const commentText = commentInput.value;
  
    if (commentText) {
        addComment(commentText, 'Bạn');
        commentInput.value = '';
    }
  });