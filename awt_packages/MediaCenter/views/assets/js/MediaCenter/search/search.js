export function SearchInPage(e, container) {

    const searchValue = e.currentTarget.value; // No trimming here
    const allWrappers = container.querySelectorAll(".wrapper");

    if (searchValue.trim() === "") {
        allWrappers.forEach((elem) => {
            if (!elem.classList.contains("searched"))
                elem.classList.remove("hidden");
        });
        return;
    }

    container.querySelectorAll(".wrapper[data-name='" + searchValue + "']").forEach((elem, index) => {
        elem.classList.add("searched");
        elem.classList.remove("hidden");
    });


    allWrappers.forEach((elem, index) => {
        if (!elem.classList.contains("searched")) {
            elem.classList.add("hidden");
        }
    });
}

