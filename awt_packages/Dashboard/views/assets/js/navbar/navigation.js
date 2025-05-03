$(document).ready((e) => {
    $(".dropdown-menu .active").parent().parent().addClass("active");

    document.querySelector(".navigation a").addEventListener("mouseenter", (e) => {
        e.currentTarget.querySelector(".dropdown-menu").classList.add("active");
    });

    document.querySelector(".navigation a").addEventListener("mouseleave", (e) => {

        setTimeout(() => {
            e.currentTarget.querySelector(".dropdown-menu").classList.remove("active");
        },500);
    });
});