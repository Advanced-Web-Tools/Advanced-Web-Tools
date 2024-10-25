export function CreateGallerySelectComponent(data) {
    console.log(data);

    let data_json;
    if (typeof data === "string") {
        try {
            data_json = JSON.parse(data);
        } catch (error) {
            console.error("Invalid JSON string", error);
            return;
        }
    } else {
        data_json = data;
    }

    let galleryContainer = document.createElement("div");
    galleryContainer.classList.add("gallery-container");

    galleryContainer.style.display = "grid";
    galleryContainer.style.gridTemplateColumns = "repeat(4, calc((100% / 4) - 5px))";
    galleryContainer.style.gridAutoRows = "300px";
    galleryContainer.style.overflowY = "auto";
    galleryContainer.style.overflowX = "hidden";
    galleryContainer.style.gap = "5px";

    galleryContainer.style.background = getComputedStyle(document.documentElement).getPropertyValue('--secondary_background').trim();
    galleryContainer.style.width = "calc(100% - 20px - 60px)";
    galleryContainer.style.margin = "30px";
    galleryContainer.style.height = "calc(100% - 20px - 60px)";
    galleryContainer.style.padding = "10px";
    galleryContainer.style.borderRadius = getComputedStyle(document.documentElement).getPropertyValue('--border_radius').trim();


    Object.values(data_json).forEach((data, index) => {
        const imageElement = document.createElement("img");
        imageElement.src = data.data.location;
        imageElement.alt = data.name;
        imageElement.setAttribute("data-id", data.data_id);
        imageElement.setAttribute("data-name", data.name);
        imageElement.style.width = "100%";
        imageElement.style.height = "100%";
        imageElement.style.objectFit = "cover";
        imageElement.style.borderRadius = "var(--border_radius)";

        galleryContainer.appendChild(imageElement);
    });


    return galleryContainer;
}
