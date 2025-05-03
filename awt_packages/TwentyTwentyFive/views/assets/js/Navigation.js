document.addEventListener("DOMContentLoaded", () => {
    const navigationItems = document.querySelectorAll(".header .nav-item.parent-item");

    navigationItems.forEach((item) => {
        const childContainer = item.querySelector(".children");

        if (childContainer) {
            childContainer.style.display = "none";

            item.addEventListener("mouseenter", () => {
                childContainer.style.display = "flex";
            });

            item.addEventListener("mouseleave", () => {
                childContainer.style.display = "none";
            });
        }
    });
});
