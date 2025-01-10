export function CreateGallerySelectComponent(data, filter = null) {
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
        if(data.data.dataType == 'image') {
            if(filter != null && filter === "image" || filter == null) {
                const imageElement = document.createElement("img");
                imageElement.src = "/" + data.data.file_location;
                imageElement.alt = data.name;
                imageElement.setAttribute("data-id", data.data_id);
                imageElement.setAttribute("data-name", data.name);
                imageElement.style.width = "100%";
                imageElement.style.height = "100%";
                imageElement.style.objectFit = "cover";
                imageElement.style.borderRadius = "var(--border_radius)";
                galleryContainer.appendChild(imageElement);
            }


        }


        if (data.data.dataType == 'video') {
            if(filter != null && filter === "video" || filter == null) {
                const videoElement = document.createElement("video");
                videoElement.style.pointerEvents = "auto";
                videoElement.style.borderRadius = "var(--border_radius)";
                videoElement.style.width = "100%";
                videoElement.style.height = "300px";
                videoElement.style.objectFit = "cover";
                videoElement.classList.add("video-selector");
                // videoElement.setAttribute("controls", "false");

                const sources = [
                    {src: "/" + data.data.file_location, type: "video/mp4"},
                    {src: "/" + data.data.file_location, type: "video/webm"},
                    {src: "/" + data.data.file_location, type: "video/avi"}
                ];

                sources.forEach(source => {
                    const sourceElement = document.createElement("source");
                    sourceElement.src = source.src;
                    sourceElement.type = source.type;
                    videoElement.appendChild(sourceElement);
                });

                galleryContainer.appendChild(videoElement);
            }
        }



    });


    return galleryContainer;
}
