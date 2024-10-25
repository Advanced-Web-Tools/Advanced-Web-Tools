export function CreateCheckBox(container, labelText, id = "", checked = false) {
    const wrapper = document.createElement("div");
    wrapper.classList.add("option");

    const label = document.createElement("label");

    label.innerText = labelText;

    const checkBox = document.createElement("input");
    checkBox.setAttribute("type", "checkbox");
    checkBox.classList.add("input-checkbox");
    checkBox.id = id;

    if(checked)
        checkBox.setAttribute("checked", "checked");


    wrapper.appendChild(label);
    wrapper.appendChild(checkBox);

    container.appendChild(wrapper);
}