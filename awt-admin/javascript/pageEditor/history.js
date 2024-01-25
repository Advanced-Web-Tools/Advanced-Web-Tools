
var updatingFromHistory = false;

var pageHistory = [];
var currentIndex = 0;

function undo() {
    updatingFromHistory = true;
    if (currentIndex - 1 >= 0) {
        $('.scene').html(pageHistory[currentIndex - 1]);
        currentIndex--;
        createEditableLayout();
    }
}

function redo() {
    updatingFromHistory = true;
    if (currentIndex + 1 <= pageHistory.length) {
        $('.scene').html(pageHistory[1 + currentIndex]);
        currentIndex++;
        createEditableLayout();
    }
}

function saveToHistory() {
    $(".replacable").remove();
    var content = $(".scene").html();

    pageHistory[currentIndex] = content;
    currentIndex++;
}