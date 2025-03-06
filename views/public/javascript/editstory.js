const STORYID = new URLSearchParams(window.location.search).get("id");

document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const storyId = urlParams.get("id");

    if (storyId) {
        fetchStoryData(storyId);
    }
});

// Hiển thị box "story" ban đầu
document.querySelector('.story').style.display = 'block';

function changeContent(element, text) {
    const storyElement = document.querySelector('.story');
    const chapElement = document.querySelector('.chap');

    if (text === 'Tạo tác phẩm') {
        storyElement.style.display = 'block';
        chapElement.style.display = 'none';
    } else if (text === 'Tạo chap') {
        storyElement.style.display = 'none';
        chapElement.style.display = 'block';
        // Khi chuyển sang tab "Tạo chap", load danh sách chapter
        if (STORYID) {
            loadChapterList(STORYID);
        } else {
            alert("Vui lòng lưu truyện trước khi tạo chương!");
        }
    }

    // Đánh dấu tab đang chọn
    const words = document.getElementsByClassName('word');
    for (let i = 0; i < words.length; i++) {
        words[i].classList.remove('selected');
    }
    element.classList.add('selected');
}

// Hàm load danh sách chapter
function loadChapterList(storyId) {
    fetch(`/api/story/${storyId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert("Không tìm thấy truyện!");
                return;
            }
            fillChapterList(data.chapters); // Gọi hàm để điền dữ liệu vào bảng
        })
        .catch(error => console.error("Lỗi khi lấy dữ liệu:", error));
}

// Hàm điền dữ liệu chapter vào bảng
function fillChapterList(chapters) {
    const chapterListDiv = document.getElementById("chapter-list");

    if (chapters && chapters.length > 0) {
        let chapterListHTML = `
            <table class="table" border="0" cellspacing="0">
                <thead>
                    <tr>
                        <th style="width:30%">Chapter</th>
                        <th style="width:20%">Thời gian</th>
                        <th style="width:30%">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>`;

        chapters.forEach(chap => {
            chapterListHTML += `
                <tr>
                    <td class="td-title"> ${chap.title}</td>
                    <td class="td-date">${new Date(chap.created_at).toLocaleDateString()}</td>
                    <td class="td-button">
                        <a class="btn-edit" href="/editchapter?chapterId=${chap.id}"><div class="td-btn">Sửa</div></a>
                        <a class="btn-delete" onclick="deleteChapter(${chap.id})"><div class="td-btn">Xóa</div></a>
                    </td>
                </tr>`;
        });

        chapterListHTML += `</tbody></table>`;
        chapterListDiv.innerHTML = chapterListHTML;
    } else {
        chapterListDiv.innerHTML = "<p>Chưa có chương nào.</p>";
    }
}

function fetchStoryData(storyId) {
    fetch(`/api/story/${storyId}`)
        .then(response => response.json())
        .then(data => {
            console.log("Data from API:", data);
            if (data.error) {
                alert("Không tìm thấy truyện!");
                return;
            }
            fillStoryData(data.story); // Chỉ điền dữ liệu story
        })
        .catch(error => console.error("Lỗi khi lấy dữ liệu:", error));
}

function fillStoryData(story) {
    document.getElementById("story-title").value = story.title;
    document.getElementById("story-content").value = story.description;
    document.getElementById("category").value = story.category;
    document.querySelector(".form-select").value = story.status;

    if (story.thumbnail) {
        document.getElementById("preview-image").src = `${story.thumbnail}`;
    }
}

function saveStory() {
    const storyId = new URLSearchParams(window.location.search).get("id");
    if (!storyId) return alert("Không tìm thấy ID truyện!");

    const updatedStory = {
        title: document.getElementById("story-title").value,
        description: document.getElementById("story-content").value,
        category: document.getElementById("category").value,
        status: document.querySelector(".form-select").value
    };

    fetch(`/api/story/${storyId}`, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(updatedStory)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Lưu truyện thành công!");
            } else {
                alert("Lỗi khi lưu truyện!");
            }
        })
        .catch(error => console.error("Lỗi khi cập nhật truyện:", error));
}

function addChapter() {
    if (!STORYID) {
        alert('Vui lòng lưu truyện trước khi thêm chương');
        return;
    }
    window.location.href = `/create-chapter?storyId=${STORYID}`; // Chuyển đến trang tạo chapter
}

function deleteChapter(chapterId) {
    if (confirm("Bạn có chắc chắn muốn xóa chương này?")) {
        fetch(`/api/chapter/${chapterId}`, {
            method: 'DELETE',
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Chương đã được xóa thành công!");
                    // Load lại danh sách chapter sau khi xóa
                    if (STORYID) {
                        loadChapterList(STORYID);
                    }
                } else {
                    alert("Lỗi khi xóa chương!");
                }
            })
            .catch(error => console.error("Lỗi khi xóa chương:", error));
    }
}

document.getElementById("image-upload").addEventListener("change", function (event) {
    selectedFile = event.target.files[0];
    if (selectedFile) {
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById("preview-image").src = e.target.result;
        };
        reader.readAsDataURL(selectedFile);
    }
});

document.getElementById("update-thumbnail").addEventListener("click", function () {
    if (!selectedFile) {
        alert("Vui lòng chọn ảnh trước!");
        return;
    }
    uploadThumbnail(selectedFile);
});

function uploadThumbnail(file) {
    const storyId = new URLSearchParams(window.location.search).get("id");
    if (!storyId) return alert("Không tìm thấy ID truyện!");

    const formData = new FormData();
    formData.append("thumbnail", file);

    fetch(`/api/story/${storyId}/thumbnail`, {
        method: "PUT",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Cập nhật ảnh bìa thành công!");
            } else {
                alert("Lỗi khi cập nhật ảnh bìa!");
            }
        })
        .catch(error => console.error("Lỗi khi cập nhật ảnh bìa:", error));
}