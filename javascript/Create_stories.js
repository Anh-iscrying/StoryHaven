//Tải ảnh từ thiết bị
document.getElementById('image-upload').addEventListener('change', function() {
    var file = this.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-image').setAttribute('src', e.target.result);
        }
        reader.readAsDataURL(file);
    }
});

//Nút lưu (Kiểm tra)
function saveStory() {
    const titleInput = document.getElementById('story-title');
    const contentTextarea = document.getElementById('story-content');
    const categoryCheckboxes = document.querySelectorAll('input[name="category[]"]');

    if (titleInput.value.trim() === '' || contentTextarea.value.trim() === '') {
      alert('Vui lòng nhập tiêu đề và mô tả của truyện.');
      return;
    }
    alert('Truyện đã được lưu thành công!');
  }
  
//Đổi box tác phẩm và chap
document.querySelector('.story').style.display = 'block';

function changeContent(element, text) {
    const storyElement = document.querySelector('.story');
    const hoiThoaiElement = document.querySelector('.chap');

    if (text === 'Tạo tác phẩm') {
        storyElement.style.display = 'block';
        hoiThoaiElement.style.display = 'none';
    } else if (text === 'Tạo chap') {
        storyElement.style.display = 'none';
        hoiThoaiElement.style.display = 'block';
    }

    const words = document.getElementsByClassName('word');
    for (let i = 0; i < words.length; i++) {
        words[i].classList.remove('selected');
    }

    element.classList.add('selected');
}
