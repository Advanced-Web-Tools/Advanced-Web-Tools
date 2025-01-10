export function PopulateHelper(titleText, DOMNode) {

    const helper = document.querySelector(".helper");

    helper.classList.add("active");

    const content = helper.querySelector(".content");
    content.innerHTML = "";

    const wrapper = document.createElement("div");
    wrapper.classList.add("wrapper");

    const header = document.createElement("div");
    header.classList.add("header");

    const title = document.createElement("h4");
    title.classList.add("title");
    title.textContent = titleText;

    const close = document.createElement("i");
    close.classList.add("fas", "fa-xmark");

    close.addEventListener("click", (e) => {
        CloseHelper(e, helper);
    });

    header.appendChild(title);
    header.appendChild(close);

    content.appendChild(header);
    wrapper.appendChild(DOMNode);

    content.appendChild(wrapper);
}
export function CloseHelper(e, helper) {
    e.stopPropagation();
    helper.querySelector(".content").innerHTML = "";
    helper.classList.remove("active");
}