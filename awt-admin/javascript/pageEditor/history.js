
var updatingFromHistory = false;

var pageHistory = [];
var currentIndex = 0;

function undo() {
    updatingFromHistory = true;
    if (currentIndex - 1 >= 0) {
        $('.pageSection').html(pageHistory[currentIndex - 1]);
        currentIndex--;
        createEditableLayout();
    }
}

function redo() {
    updatingFromHistory = true;
    if (currentIndex + 1 <= pageHistory.length) {
        $('.pageSection').html(pageHistory[1 + currentIndex]);
        currentIndex++;
        createEditableLayout();
    }
}

function saveToHistory() {
    $(".replacable").remove();
    var content = $(".pageSection").html();

    pageHistory[currentIndex] = content;
    currentIndex++;
}