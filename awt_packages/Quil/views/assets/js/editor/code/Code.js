
document.addEventListener("DOMContentLoaded", (e) => {
    const codemirror = CodeMirror.fromTextArea(document.querySelector(".editor .code"), {
        lineNumbers: true,
        mode: "htmlmixed",
        theme: "default",
    });

    codemirror.setSize("100%", "100%");
});