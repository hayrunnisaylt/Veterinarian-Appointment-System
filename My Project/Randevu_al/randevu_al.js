
const modal = document.getElementById("randevuAlModal");
const btn = document.getElementById("randevuAlBtn");
const span = document.querySelector(".close");

btn.onclick = function () {
    modal.style.display = "block";
	modal.style.zIndex= "1111";
};

span.onclick = function () {
    modal.style.display = "none";
};

window.onclick = function (event) {
    if (event.target === modal) {
        modal.style.display = "none";
    }
};

closeModalButton.addEventListener("click", closeModal);

window.addEventListener("click", function (event) {
    if (event.target === modal) {
        closeModal();
    }
});

