import {PopulateHelper} from "../../../../../../Dashboard/js/ui/Helper.js";

export function EnlargeImage(e) {
    const index = e.currentTarget.getAttribute("data-index");
    const image = document.querySelector("img[data-index='" + index + "']").cloneNode(true);
    PopulateHelper(image.getAttribute("data-name"), image);
}
