function openSubmenu(element, hidden_element, hidden_class) {
    $(element).click(function(){
        $(hidden_element).toggleClass(hidden_class);
    });
}