
function initShortcuts() {
    $(document).bind("keydown", function (event) {
        if (event.key === "Delete" || event.keyCode === 46) {
            $(".selected").remove();
            saveToHistory();
        }
        if (event.ctrlKey) ctrlPressed = true;
    });

    $(document).bind("keyup", function (event) {
        if (ctrlPressed && event.key === "z") {
            updateFromHistory();
            createEditableLayout();
        }
        if (event.ctrlKey) ctrlPressed = false;
    });
    $(document).on('contextmenu', function (event) {
        event.preventDefault();
        contextMenu();
    });
}