function textEditor(input, output) {
    text = $(input).val();
    $(output).html(text);
}

function loadText(input, output) {
    var text = $(input).text();
    $(output).val(text);
    loaded = 1;
}