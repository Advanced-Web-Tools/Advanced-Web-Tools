export function NotPage(block) {
    return !$(block).is(".page");
}

export function TextElement(block) {
    const htmlTextElements = [
        "p",
        "h1",
        "h2",
        "h3",
        "h4",
        "h5",
        "h6",
        "span",
        "strong",
        "em",
        "b",
        "i",
        "u",
        "mark",
        "small",
        "sup",
        "sub",
        "del",
        "ins",
        "code",
        "pre",
        "blockquote",
        "cite",
        "abbr",
        "q",
        "a",
        "li",
    ];

    const check = function (block, htmlTextElements) {
        return htmlTextElements.some((element) => {
            return $(block)[0].nodeName.toLowerCase() === element;
        });
    }

    return check(block, htmlTextElements) && !!$(block)[0].textContent.trim();
}
