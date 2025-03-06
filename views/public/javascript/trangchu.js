// Xử lý tìm kiếm (di chuyển từ event listener)
function performSearch(query) {
    fetch("/api/stories") // Gọi API lại để lọc dữ liệu
        .then((response) => response.json())
        .then((data) => {
            if (data) {
                const filteredStories = data.filter((story) =>
                    story.title.toLowerCase().includes(query.toLowerCase())
                );
                console.log(filteredStories);
                renderStories(filteredStories);
            }
        })
        .catch((error) => console.error("Search error:", error));
}

// Hàm render danh sách truyện (sửa đổi cho phù hợp với cấu trúc HTML mới)
function renderStories(stories) {
    const storyContainer = document.getElementById("storyContainer");
    storyContainer.innerHTML = ""; // Xóa danh sách cũ

    stories.forEach((story) => {
        const storyCard = document.createElement("a");
        storyCard.href = `/story/${story.id}`;
        storyCard.className = "col-6 col-md-3 mb-3"; // Giữ class để responsive
        storyCard.innerHTML = `
            <div class="card">
                <img src="${story.thumbnail || "../images/default.jpg"}" class="card-img-top" alt="${story.title}">
                <div class="card-body">
                    <h5 class="card-title">${story.title}</h5>
                </div>
            </div>
        `;
        storyContainer.appendChild(storyCard);
    });
}

// Gọi API để lấy danh sách truyện (loại bỏ phân trang)
function fetchStories() {
    fetch("/api/stories") // Thay bằng API thật của bạn
        .then((response) => response.json())
        .then((data) => {
            if (data) {
                renderStories(data);
            } else {
                console.error("Lỗi khi tải dữ liệu:", data.error);
            }
        })
        .catch((error) => console.error("Fetch error:", error));
}

// Xử lý tìm kiếm
document.querySelector(".search-form").addEventListener("submit", function (event) {
    event.preventDefault();
    const searchQuery = document.querySelector(".search-form input").value;
    performSearch(searchQuery);
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

// Gọi fetchStories khi trang tải xong
fetchStories();