export function CreateContainerComponent(title = "Container", useTitle = true) {
    const container = document.createElement("div");
    container.classList.add("options-container");

    const titleElement = document.createElement("h3");
    titleElement.classList.add("options-title");
    titleElement.innerText = title;

    if(useTitle) {
        container.appendChild(titleElement);
        container.appendChild(document.createElement("hr"));
    }

    return container;
}