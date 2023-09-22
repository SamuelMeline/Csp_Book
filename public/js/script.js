document.addEventListener("DOMContentLoaded", function () {
    const deleteButtons = document.querySelectorAll(".delete-button");
    const modal = document.getElementById("myModal");
    const modalConfirm = document.getElementById("modal-confirm");
    const modalCancel = document.getElementById("modal-cancel");

    deleteButtons.forEach((button) => {
        button.addEventListener("click", function (event) {
            event.preventDefault();

            const url = this.getAttribute("href");
            modal.style.display = "block";

            modalConfirm.addEventListener("click", function () {
                window.location.href = url;
                modal.style.display = "none";
            });

            modalCancel.addEventListener("click", function () {
                modal.style.display = "none";
            });
        });
    });
});

$(document).ready(function() {
    $("#menu-icon").click(function() {
        $("#menu-dropdown").toggle();
    });
});