var selectedTextCopy;

function setTextOptions($block, defaultStyle) {
    var defaultFontSize = defaultStyle ? defaultStyle.match(/font-size:\s*(\S+);/) : null;
    var defaultTextColor = defaultStyle ? defaultStyle.match(/color:\s*([^;]+);/) : null;

    // Extract the first match from the array if available
    defaultFontSize = defaultFontSize ? defaultFontSize[1].replace('px', '') : 15;
    defaultTextColor = defaultTextColor ? defaultTextColor[1] : null;
    defaultTextColor = rgbToHex(defaultTextColor);

    var options = '<p>Text options</p>';
    options += '<input type="number" class="font-size-input" value="' + (defaultFontSize ? defaultFontSize : '') + '" placeholder="Font Size">';
    options += '<label for="text-color">Text color:</label>';
    options += '<input type="color" class="text-color-input" id="text-color" value="' + (defaultTextColor ? defaultTextColor : '') + '" placeholder="Text Color">';
    options += '<div class="alignment-buttons"><button class="align-left button"><i class="fa-solid fa-align-left"></i></button>';
    options += '<button class="align-center button"><i class="fa-solid fa-align-center"></i></button>';
    options += '<button class="align-right button"><i class="fa-solid fa-align-right"></i></button></div>';

    $(".block-options").append(options);

    $(".font-size-input").on("input", function () {
        $selection.css("font-size", $(this).val() + "px");
    });

    $(".text-color-input").on("input", function () {
        $selection.css("color", $(this).val());
    });

    // Set initial button states based on current text alignment
    var currentAlignment = $block.css("text-align");
    $(".align-left").prop("disabled", currentAlignment === "left");
    $(".align-left").prop("disabled", currentAlignment === "");
    $(".align-center").prop("disabled", currentAlignment === "center");
    $(".align-right").prop("disabled", currentAlignment === "right");

    // Bind click event handlers to update text alignment
    $(".align-left").on("click", function () {
        $block.css("text-align", "left");
        $(".align-left").prop("disabled", true);
        $(".align-center").prop("disabled", false);
        $(".align-right").prop("disabled", false);
    });

    $(".align-center").on("click", function () {
        $block.css("text-align", "center");
        $(".align-left").prop("disabled", false);
        $(".align-center").prop("disabled", true);
        $(".align-right").prop("disabled", false);
    });

    $(".align-right").on("click", function () {
        $block.css("text-align", "right");
        $(".align-left").prop("disabled", false);
        $(".align-center").prop("disabled", false);
        $(".align-right").prop("disabled", true);
    });
}


function hasTextChild($block) {
    var allowedTags = ["p", "h1", "h2", "h3", "h4", "h5", "h6", "a", "strong", "em", "b", "i", "u", "li"];

    var result = $block
        .find("*")
        .filter(function () {
            var tagName = this.tagName.toLowerCase();
            return allowedTags.includes(tagName) || this.nodeType === 3 && $.trim(this.nodeValue).length > 0;
        });

    return result;
}


function isText($block) {

    var result = false;
    var allowedTags = ["p", "h1", "h2", "h3", "h4", "h5", "h6", "a", "strong", "em", "b", "i", "u", "li"];
    if(allowedTags.includes($(".selected").prop('nodeName').toLowerCase())) result = true;
    console.log(result);
    if(hasTextChild($block).length > 0) result = true;
    
    console.log(result);

    return result;
}


var timeout;

function isEditing($block) {


    var allowedTags = ["p", "h1", "h2", "h3", "h4", "h5", "h6", "a", "strong", "em", "b", "i", "u", "li"];

    var isEditing = false;

    if(document.activeElement) isEditing = true;

    console.log(isEditing);

    if (!isEditing) {
        return false;
    }

    var focusedElement = $('.pageSection').find(':focus');
    var isAllowedContent = focusedElement.is(allowedTags.join('[contenteditable=true],') + '[contenteditable=true]');

    if (isAllowedContent) {
        focusedElement.off('focus.textEditor');
        focusedElement.off('blur');
        focusedElement.on('focus.textEditor', enableTextEditor(focusedElement));
        focusedElement.on('blur', function () { disableTextEditor() });
    }

    return isAllowedContent;
}


function disableTextEditor() {
    let $textEditor = $('.textEditor');

    timeout = setTimeout(function () {
        if (!$textEditor.hasClass('hidden')) {
            $textEditor.addClass('hidden');
            $(".preview").css('top', 0);
        }
    }, 1);
}


function enableTextEditor(element) {

    let $textEditor = $('.textEditor');

    clearTimeout(timeout);

    if ($textEditor.hasClass('hidden')) {
        $textEditor.removeClass('hidden');
    }

    let offsetTop = element.offset().top - $textEditor.outerHeight() * 2;
    if(offsetTop < $(".preview").offset().top) offsetTop = element.offset().top + $textEditor.outerHeight() / element.offset().top;

    let offsetLeft = element.offset().left + (element.outerWidth() - $textEditor.outerWidth()) / 2;

    if(offsetLeft < $(".preview").offset().left) offsetLeft = element.offset().left - (element.outerWidth() + $textEditor.outerWidth()) / $textEditor.outerWidth();


    $textEditor.css({
        'top': offsetTop,
        'left': offsetLeft
    });
    

    textEditor(element)
}

function textEditor(element) {

    const $italic = $(".textEditor #turnItalic");
    const $bold = $(".textEditor #turnBold");
    const $hyperLink = $(".textEditor #addHyperLink");
    const $breakLine = $(".textEditor #breakLine");

    $hyperLink.off("click");
    $bold.off("click");
    $italic.off("click");
    $breakLine.off("click");

    var selection = window.getSelection();
    var range = selection.getRangeAt(0);

    $(".textEditor button").mousedown(function(e) { 
        e.preventDefault(); 
    });    

    $hyperLink.on("click", function (e) {
        hyperLink(range);
        element.focus();
    });

    $bold.on("click", function (e) {
        bold(range);
        element.focus();
    });

    $italic.on("click", function (e) {
        italic(range);
        element.focus();
    });

    $breakLine.on("click", function (e) {
        breakLine(range);
        element.focus();
    });

}

function restoreSelection(range) {
    var selection = window.getSelection();
    selection.removeAllRanges();
    selection.addRange(range);
}

function isRangeWrappedInTag(range, tagName) {
    var parentNode = range.commonAncestorContainer.parentNode;
    return parentNode.nodeName === tagName;
}

function findAncestorWithTagName(node, tagName) {
    while (node.parentNode) {
        node = node.parentNode;
        if (node.nodeName === tagName) {
            return node;
        }
    }
    return null;
}

function removeTagsFromRange(range, tagName) {
    var startContainer = range.startContainer;
    var endContainer = range.endContainer;

    // Find the closest ancestor that matches the specified tag name
    var ancestorStart = findAncestorWithTagName(startContainer, tagName);
    var ancestorEnd = findAncestorWithTagName(endContainer, tagName);

    if (ancestorStart && ancestorEnd) {
        // Replace the tag with its content
        var content = ancestorStart.innerHTML;
        var replacementNode = document.createRange().createContextualFragment(content);
        ancestorStart.parentNode.replaceChild(replacementNode, ancestorStart);
        saveToHistory();
    }
}


function hyperLink(range) {

    if (isRangeWrappedInTag(range, 'A')) {
        removeTagsFromRange(range, 'A');
        saveToHistory();
        return;
    }


    var $dialog = $('.dialog');
    $dialog.toggleClass("active");

    var content = $('.dialog .content');
    content.empty();

    fetchPages('?page=Home', function (selector) {
        content.append("<p>Select page</p>");
        content.append(selector);
        $('.dialog select').addClass('select');
        $('.dialog select').css("width", "250px");
        $('.dialog select').css("padding", "10px 20px 10px 5px");
        content.append("<hr style='width: 100%; opacity: 0.3;'>");
        content.append("<p>Or enter custom link:</p>");
        content.append("<input class='input' id='enterURL' placeholder='Enter URL'>");
        content.append("<button class='button' id='insertURL' >Insert URL</button>");

        $('.dialog select').on('change', function () {
            var linkUrl = $('.dialog select').val();

            if (linkUrl) {
                let html = "<a href='" + linkUrl + "'>" + range.toString() + "</a>";

                range.deleteContents();

                range.insertNode(document.createRange().createContextualFragment(html));

                saveToHistory();
            }

            $dialog.removeClass("active");

        });
        
        document.querySelector('.dialog #insertURL').addEventListener('click', function () {
            var linkUrl = document.querySelector('.dialog #enterURL').value;
    
            if (linkUrl) {
                let html = "<a href='" + linkUrl + "'>" + range.toString() + "</a>";
    
                range.deleteContents();
    
                range.insertNode(document.createRange().createContextualFragment(html));
    
                saveToHistory();
            }
    
            $dialog.removeClass("active");
    
        });
    });

}

function breakLine(range) {
    var html = range.toString() + "<br>";
    range.deleteContents();

    range.insertNode(document.createRange().createContextualFragment(html));
    saveToHistory();
}

function bold(range) {

    if (isRangeWrappedInTag(range, 'B')) {
        removeTagsFromRange(range, 'B');
        saveToHistory();
        return;
    }

    var html = "<b>" + range.toString() + "</b>";
    range.deleteContents();

    range.insertNode(document.createRange().createContextualFragment(html));
    saveToHistory();
}

function italic(range) {

    if (isRangeWrappedInTag(range, 'I')) {
        removeTagsFromRange(range, 'I');
        saveToHistory();
        return;
    }

    var html = "<i>" + range.toString() + "</i>";
    range.deleteContents();

    range.insertNode(document.createRange().createContextualFragment(html));
    saveToHistory();
}


var option = new BlockOption(isText, setTextOptions, isEditing);

blockOptions.push(option);