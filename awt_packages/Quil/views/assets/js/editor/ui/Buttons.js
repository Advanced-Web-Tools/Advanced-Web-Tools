export function CreateButtonComponent(container, text, id, type = "primary", useTitle = false, title = "") {
    const button = document.createElement("button");
    button.classList.add(`btn_${type}`);
    button.classList.add("option");
    button.id = id;
    button.innerHTML = text;

    if(type !== "grouped")
        button.style.width = "fit-content";

    if (useTitle)
        button.setAttribute("title", title);

    container.appendChild(button);

    return button;
}

export function CreateButtonGroupComponent(container, labelText = "Button group:", useLabel = true, contents = ["Button 1", "Button 2", "Button 3"], ids = ["button1", "button2", "button3"], useTitle = false, titles = []) {

    const group = document.createElement("div");
    group.classList.add("option");
    group.classList.add("button-group");

    if(contents.length === 4)
        group.classList.add("four-buttons");


    if (useLabel) {
        const label = document.createElement("label");
        label.textContent = labelText;
        group.appendChild(label);
    }

    const wrapper = document.createElement("div");
    wrapper.classList.add("button-wrapper");

    contents.forEach((content, index) => {
        if (!useTitle)
            CreateButtonComponent(wrapper, content, ids[index], "grouped");
        if (useTitle)
            CreateButtonComponent(wrapper, content, ids[index], "grouped", true, titles[index]);
    });

    group.appendChild(wrapper);

    container.appendChild(group);
}