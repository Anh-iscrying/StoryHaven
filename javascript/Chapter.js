//Thanh menu
const menuToggle = document.querySelector('.fa');
const menuContainer = document.querySelector('.menu');

menuToggle.addEventListener('click', () => {
menuContainer.classList.toggle('open');
});

//Đổi màu vote
const Vote = document.getElementById('Vote');
Vote.addEventListener('click', function() {
Vote.classList.toggle('clicked');
});

//Bình luận
function getRandomAvatar() {
return `https://i.pravatar.cc/50?u=${Math.random()}`;
}

function getRandomUsername() {
return `Người dùng ${Math.floor(Math.random() * 100)}`;
}

// Xử lý sự kiện submit form bình luận chính
document.getElementById("commentForm").addEventListener("submit", function(event) {
event.preventDefault();
const commentText = document.getElementById("commentInput").value.trim();
if (commentText === "") return;


const comment = createCommentElement(commentText);

document.getElementById("comments").prepend(comment);

document.getElementById("commentInput").value = "";
});

// Hàm tạo phần tử bình luận
function createCommentElement(commentText) {
const comment = document.createElement("li");
comment.classList.add("comment");
comment.innerHTML = `
    <div class="comments-detail">
        <div class="avt">
            <img src="https://i.pravatar.cc/50?u=${Math.random()}" alt="Avatar">
            <div class="nick-name">Người dùng ${Math.floor(Math.random() * 100)}</div>
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
            <form id="commentForm" class="row">
                <div class="col-10"><textarea type="text" class="reply-input" placeholder="Nhập bình luận của bạn..." required></textarea></div>
                <div class="col-2"><button class="send-reply"><i class="fas fa-paper-plane"></i></button></div>
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

    const reply = createReplyElement(replyText);

    repliesContainer.appendChild(reply);

    replyForm.style.display = "none";

    replyCount.textContent = parseInt(replyCount.textContent) + 1;

    repliesContainer.style.display = "block";

    replyInput.value = "";
});

return comment;
}

// Hàm tạo phần tử phản hồi
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