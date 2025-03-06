const editorPicks = [
    { title: "Xích Tâm Tuần Thiên", image: "images/xich-tam-tuan-thien.jpg" },
    { title: "Truyện Ma Đô Thị", image: "images/truyen-ma-do-thi.jpg" },
    { title: "Chấp Ma", image: "images/chap-ma.jpg" },
    { title: "Trận Hỏi Trường Sinh", image: "images/tran-hoi-truong-sinh.jpg" }
];

const hotStories = [
    { title: "Doraemon", image: "images/doraemon.jpg" },
    { title: "Dragon Ball", image: "images/dragon-ball.jpg" },
    { title: "Thám tử lừng danh Conan", image: "images/conan.jpg" },
    { title: "Attack on Titan", image: "images/attack-on-titan.jpg" }
];

function displayStoryCards(list, containerId) {
    const container = document.getElementById(containerId);
    container.innerHTML = "";

    list.forEach(story => {
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
        container.appendChild(storyElement);
    });
}

document.addEventListener("DOMContentLoaded", () => {
    displayStoryCards(editorPicks, "editorPicksList");
    displayStoryCards()(hotStories, "hotStoriesList");
});
function displayStoryCards(list, containerId, type) {
    const container = document.getElementById(containerId);
    container.innerHTML = "";

    list.forEach(story => {
        const storyElement = document.createElement("div");
        storyElement.classList.add("col-md-3", "mb-3");
        storyElement.innerHTML = `
            <div class="card story-card ${type}">
                <img src="${story.image}" class="card-img-top" alt="${story.title}">
                <div class="card-body">
                    <h5 class="card-title">${story.title}</h5>
                </div>
            </div>
        `;
        container.appendChild(storyElement);
    });
}

document.addEventListener("DOMContentLoaded", () => {
    displayStoryCards(editorPicks, "editorPicksList", "editor-pick");
    displayStoryCards(hotStories, "hotStoriesList", "hot-story");
});