export function CreateColorInput(html, label = "Color:", id = "", value = "transparent")
{
    const wrapper = document.createElement("div");
    wrapper.classList.add("options-container");

    const labelElement = document.createElement("label");
    labelElement.textContent = label;
    labelElement.setAttribute("for", id);

    const colorWrapper = document.createElement("div");
    colorWrapper.classList.add("clr-field");

    const inputElement = document.createElement("input");
    inputElement.setAttribute("data-coloris", "true");
    inputElement.setAttribute("type", "text");
    inputElement.classList.add("color-input");
    inputElement.id = id;
    inputElement.style.color = "transparent";
    inputElement.style.backgroundColor = value;

    colorWrapper.appendChild(inputElement);

    wrapper.appendChild(labelElement);
    wrapper.appendChild(colorWrapper);

    html.appendChild(wrapper);

    Coloris({
        el: '.color-input',
        theme: 'pill',
        themeMode: 'dark',
        alpha: true,
        wrap: true,
        formatToggle: true,
        closeButton: true,
        clearButton: true,
    });

    inputElement.addEventListener("input", (e) => {
        e.preventDefault();
        e.currentTarget.style.background = e.currentTarget.value;
    });

    return html;
}