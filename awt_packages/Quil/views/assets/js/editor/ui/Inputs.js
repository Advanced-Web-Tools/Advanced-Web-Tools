export function CreateInputComponent(container, type, placeholder, labelText = "Input:", id = "input", value = "0", title = "") {
    const option = document.createElement("div");
    option.classList.add("option");

    const input = document.createElement("input");
    input.id = id;
    input.setAttribute("placeholder", placeholder);
    input.setAttribute("type", type);
    input.value = value;
    input.setAttribute("title", title);

    const label = document.createElement("label");
    label.innerText = labelText;
    label.setAttribute("for", id);

    option.appendChild(label);
    option.appendChild(input);

    container.appendChild(option);

}

export function CreateInputWithUnitComponent(container, type, placeholder, labelText = "Input:", id = "input-with-units", value = "0", units = ['px', 'pt', 'em', 'rem', '%'], title = "") {
    const option = document.createElement("div");
    option.classList.add("input-unit");

    const input = document.createElement("input");
    input.id = id;
    input.setAttribute("placeholder", placeholder);
    input.setAttribute("type", type);
    input.value = value;
    input.setAttribute("title", title);

    const label = document.createElement("label");
    label.innerText = labelText;
    label.classList.add("lg");
    label.setAttribute("for", id);

    option.appendChild(label);
    option.appendChild(input);
    CreateSelect(option, id + "-unit", units);
    container.appendChild(option);
}


export function CreateSelect(container, id, options) {
    const wrapper = document.createElement("div");
    wrapper.classList.add("option");
    const select = document.createElement("select");
    select.id = id;
    options.forEach((option, index) => {
        const optionElement = document.createElement("option");
        optionElement.innerText = option;
        select.appendChild(optionElement);
    });
    wrapper.appendChild(select);
    container.appendChild(wrapper);
    return [wrapper, select];
}
