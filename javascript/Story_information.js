//Đổi màu khi ấn vào Theo dõi
const Follow = document.getElementById('Follow');
Follow.addEventListener('click', function() {
    Follow.classList.toggle('clicked');
});


//Hiện thị thể loại truyện
document.addEventListener("DOMContentLoaded", function() {
    const categoryToggle = document.getElementById("category-toggle");
    const categoryList = document.getElementById("category-list");
    const categoryToggle1 = document.getElementById("category-toggle1");
    const categoryList1 = document.getElementById("category-list1");

    function hideAllLists() {
        categoryList.style.display = "none";
        categoryList1.style.display = "none";
    }

    categoryToggle.addEventListener("click", function(event) {
        if (categoryList.style.display === "none" || categoryList.style.display === "") {
            hideAllLists(); 
            categoryList.style.display = "block";
        } else {
            categoryList.style.display = "none";
        }
        event.stopPropagation(); 
    });

    categoryToggle1.addEventListener("click", function(event) {
        if (categoryList1.style.display === "none" || categoryList1.style.display === "") {
            hideAllLists(); 
            categoryList1.style.display = "block";
        } else {
            categoryList1.style.display = "none";
        }
        event.stopPropagation(); 
    });

    document.addEventListener("click", function() {
        hideAllLists(); 
    });
});
