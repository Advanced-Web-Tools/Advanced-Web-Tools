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

    return $block
        .find("*")
        .filter(function () {
            var tagName = this.tagName.toLowerCase();
            return allowedTags.includes(tagName) || this.nodeType === 3 && $.trim(this.nodeValue).length > 0;
        });
}