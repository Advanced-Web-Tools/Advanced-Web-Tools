
var updatingFromHistory = false;

var pageHistory = [];
var currentIndex = -1;

function updateFromHistory() {
    updatingFromHistory = true;
    if (currentIndex !== 0) $('.pageSection').html(pageHistory[currentIndex - 1]);
    createEditableLayout();
}

function saveToHistory() {
    var content = $(".pageSection").html();
    pageHistory.push(content);
    currentIndex++;
}