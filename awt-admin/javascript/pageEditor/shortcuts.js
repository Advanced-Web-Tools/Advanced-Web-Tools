
function initShortcuts() {
    $(document).bind("keydown", function (event) {
        if (event.key === "Delete" || event.keyCode === 46 && !$('input').is(":focus")) {
            $(".selected").remove();
            saveToHistory();
        }
        if (event.ctrlKey) ctrlPressed = true;
    });

    $(document).bind("keyup", function (event) {
        if (ctrlPressed && event.key === "z" &&  !$('input').is(":focus")) {
            undo();
            createEditableLayout();
        }
        if (ctrlPressed && event.key === "y" && !$('input').is(":focus")) {
            redo();
            createEditableLayout();
        }
        if (event.ctrlKey) ctrlPressed = false;
    });

    $(document).on('contextmenu', function (event) {
        event.preventDefault();
        contextMenu();
    });
}