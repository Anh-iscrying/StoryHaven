 document.getElementById('searchInput').addEventListener('input', function () {
        let filter = this.value.toLowerCase();
        let stories = document.querySelectorAll('#storyList li');
        
        stories.forEach(function (story) {
            let text = story.textContent.toLowerCase();
            story.style.display = text.includes(filter) ? '' : 'none';
        });
    });

   
    const recommendedStories = [
        { title: "Thế Giới Hoàn Mỹ", image: "images/the-gioi-hoan-my.jpg" },
        { title: "Phàm Nhân Tu Tiên", image: "images/pham-nhan-tu-tien.jpg" },
        { title: "Đấu Phá Thương Khung", image: "images/dau-pha-thuong-khung.jpg" },
        { title: "Vạn Cổ Chí Tôn", image: "images/van-co-chi-ton.jpg" }
    ];

    const recommendedList = document.getElementById("recommendedList");
    recommendedStories.forEach(story => {
        const storyElement = document.createElement("div");
        storyElement.classList.add("col-md-3", "mb-3");
        storyElement.innerHTML = `
            <div class="card">
                <img src="${story.image}" class="card-img-top" alt="${story.title}">
                <div class="card-body">
                    <h5 class="card-title">${story.title}</h5>
                </div>
            </div>
        `;
        recommendedList.appendChild(storyElement);
    });

    let currentPage = 1;
    const storiesPerPage = 5;
    let allStories = []; // Lưu tất cả truyện từ API
    let filteredStories = []; // Lưu danh sách truyện đã lọc theo tìm kiếm
    
    async function fetchStories() {
        try {
            const response = await fetch('/api/stories'); // Gọi API lấy danh sách truyện
            const data = await response.json();
            allStories = data; // Lưu toàn bộ truyện
            filteredStories = allStories; // Mặc định hiển thị tất cả
            displayStories(); // Hiển thị trang đầu tiên
        } catch (error) {
            console.error('Lỗi khi tải danh sách truyện:', error);
        }
    }
    
    function displayStories() {
        const storyList = document.getElementById('storyList');
        storyList.innerHTML = ''; // Xóa danh sách cũ
    
        // Xác định truyện nào sẽ được hiển thị trong trang hiện tại
        const startIndex = (currentPage - 1) * storiesPerPage;
        const endIndex = startIndex + storiesPerPage;
        const currentStories = filteredStories.slice(startIndex, endIndex);
    
        // Hiển thị danh sách truyện của trang hiện tại
        currentStories.forEach(story => {
            const li = document.createElement('li');
            li.classList.add('list-group-item');
            li.textContent = story.title;
            storyList.appendChild(li);
        });
    
        // Cập nhật thông tin phân trang
        document.getElementById('page-info').textContent = `Trang ${currentPage} / ${Math.ceil(filteredStories.length / storiesPerPage)}`;
        document.getElementById('prev-btn').disabled = currentPage === 1;
        document.getElementById('next-btn').disabled = endIndex >= filteredStories.length;
    }
    
    // ✅ Tìm kiếm truyện trong toàn bộ danh sách
    document.getElementById('searchInput').addEventListener('input', function () {
        const query = this.value.trim().toLowerCase();
    
        if (query === '') {
            filteredStories = allStories; // Nếu không nhập gì, hiển thị toàn bộ
        } else {
            filteredStories = allStories.filter(story => story.title.toLowerCase().includes(query));
        }
    
        currentPage = 1; // Reset về trang đầu tiên khi tìm kiếm
        displayStories();
    });
    
    // Xử lý khi bấm nút "Trang Trước"
    document.getElementById('prev-btn').addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            displayStories();
        }
    });
    
    // Xử lý khi bấm nút "Trang Sau"
    document.getElementById('next-btn').addEventListener('click', () => {
        if (currentPage * storiesPerPage < filteredStories.length) {
            currentPage++;
            displayStories();
        }
    });
    
    // Gọi API khi trang tải xong
    document.addEventListener('DOMContentLoaded', fetchStories);


    