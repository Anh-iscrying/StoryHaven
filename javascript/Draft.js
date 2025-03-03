//Lấy nội trên web khi người dùng nhập
const storyTitle = document.getElementById('story-title');

storyTitle.addEventListener('input', function() {
    const newTitle = storyTitle.innerText;
    storyTitle.textContent = newTitle;
});

// Ngăn người dùng nhập xuống dòng khi nhấn phím Enter
storyTitle.addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        const text = this.innerText.trim();
        this.innerText = text;
    }
});

// Thêm nội dung vào phần tử div khi trang web được tải
document.addEventListener('DOMContentLoaded', function() {
    const storyEditor = document.querySelector('.story-editor');
    const newParagraph = document.createElement('p');
    storyEditor.appendChild(newParagraph);
});

//Nút lưu
function saveStory() {
    const storyTitle = document.getElementById('story-title').innerText;
    const storyContent = document.querySelector('.story-editor').innerText;
    alert("Bạn đã lưu thành công!");
}

//Nút đăng tải
function uploadStory() {
  const storyTitle = document.getElementById('story-title').innerText;
  const storyContent = document.querySelector('.story-editor').innerText;
  alert("Bạn đã đăng tải thành công!");
}

  