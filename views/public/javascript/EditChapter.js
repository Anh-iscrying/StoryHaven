const CHAPTERID = new URLSearchParams(window.location.search).get('chapterId');

document.addEventListener('DOMContentLoaded', function () {
    console.log("Chapter ID:", CHAPTERID);
    if (CHAPTERID) {
        fetchChapterData(CHAPTERID);
    } else {
        console.error('Chapter ID is missing in URL parameters.');
        alert('Chapter ID không tồn tại!'); // Thêm thông báo cho người dùng
        // Có thể redirect về trang danh sách chapter hoặc trang chủ
    }
});

async function fetchChapterData(chapterId) {
    try {
        const response = await fetch(`/api/chapter/${chapterId}`); // Đảm bảo URL chính xác
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`); // Xử lý lỗi HTTP
        }
        const data = await response.json();

        console.log("Chapter data:", data);

        if (data.error) {
            console.error('Error fetching chapter data:', data.error);
            alert('Không tìm thấy chương!');
            return;
        }

        // Điền dữ liệu vào form
        document.getElementById('story-title').innerText = data.title;
        document.querySelector('.story-editor').innerText = data.content;
        document.getElementById('chapter-number').textContent = `Chương ${data.chapter_number}`;

    } catch (error) {
        console.error('Error fetching chapter data:', error);
        alert('Lỗi khi tải dữ liệu chương: ' + error.message); // Thêm thông tin lỗi cụ thể
    }
}

async function saveChapter() {
    const chapterId = CHAPTERID;
    const title = document.getElementById('story-title').innerText;
    const content = document.querySelector('.story-editor').innerText;
    const chapter_number = parseInt(document.getElementById('chapter-number').value);

    // Validation (kiểm tra dữ liệu trước khi gửi)
    if (!title || !content) {
        alert('Vui lòng nhập tiêu đề và nội dung!');
        return;
    }

    try {
        const response = await fetch(`/api/chapter/${chapterId}`, { // Đảm bảo URL chính xác
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ title, content, chapter_number: parseInt(chapter_number) })
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`); // Xử lý lỗi HTTP
        }

        const data = await response.json();

        if (data.error) {
            console.error('Error saving chapter:', data.error);
            alert('Lỗi khi lưu chương: ' + data.error);
            return;
        }

        alert('Lưu chương thành công!');
    } catch (error) {
        console.error('Error saving chapter:', error);
        alert('Lỗi khi lưu chương: ' + error.message); 
    }
}

