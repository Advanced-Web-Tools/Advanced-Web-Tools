import {EnlargeImage} from "./image/enlarge";
import {SearchInPage} from "./search/search";

document.addEventListener("DOMContentLoaded", () => {
    const enlargeButtons = document.querySelectorAll("#enlarge");

    enlargeButtons.forEach(button => {
        button.addEventListener("click", (e) => {
            e.stopPropagation();
            EnlargeImage(e);
        });
    });


    document.querySelector("#search").addEventListener("input", (e) => {
        SearchInPage(e, document.querySelector(".content_wrapper"));
    });
});
