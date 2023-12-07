function setDefaultOptions($block, defaultStyle) {
    var defaultWidth = defaultStyle ? defaultStyle.match(/width:\s*((?:[^;]+)*)/) : null;
    var defaultHeight = defaultStyle ? defaultStyle.match(/height:\s*((?:[^;]+)*)/) : null;
    var defaultBackgroundColor = defaultStyle ? defaultStyle.match(/background:\s*([^;]+);/) : null;
    var backgroundImage = defaultStyle ? defaultStyle.match(/background-image:\s*([^;]+);/) : null;
    var backgroundPosition = defaultStyle ? defaultStyle.match(/background-position:\s*([^;]+);/) : null;


    var backgroundRepeat = $block.css("background-repeat");
    
    
    var defaultBackgroundColor = $block.css("background-color");
    defaultBackgroundColor = rgbToHex(defaultBackgroundColor);
    

    var defaultMarginTop = $block.css("margin-top");
    var defaultMarginRight = $block.css("margin-right");
    var defaultMarginBottom = $block.css("margin-bottom");
    var defaultMarginLeft = $block.css("margin-left");
    


    var defaultPaddingTop = $block.css("padding-top");
    var defaultPaddingRight = $block.css("padding-right");
    var defaultPaddingBottom = $block.css("padding-bottom");
    var defaultPaddingLeft = $block.css("padding-left");

    var borderRadiusTopL = $block.css("border-top-left-radius");
    var borderRadiusTopR = $block.css("border-top-right-radius");
    var borderRadiusBotR = $block.css("border-bottom-right-radius");
    var borderRadiusBotL = $block.css("border-bottom-left-radius");


    var options = '<p>Block options</p>';

    options += '<label for="margin">Margin:</label>';
    options += '<div class="input-group">';
    options += '<input type="text" id="margin" class="margin-input-top" value="' + (defaultMarginTop) + '" placeholder="Top">';
    options += '<input type="text" id="margin" class="margin-input-right" value="' + (defaultMarginRight) + '" placeholder="Right">';
    options += '<i onclick="linkInputs(this);" class="fa-solid fa-link"></i>';
    options += '<input type="text" id="margin" class="margin-input-bottom" value="' + (defaultMarginBottom) + '" placeholder="Bottom">';
    options += '<input type="text" id="margin" class="margin-input-left" value="' + (defaultMarginLeft) + '" placeholder="Left">';
    options += '</div>';
    options += '<label for="padding">Padding:</label>';
    options += '<div class="input-group">';
    options += '<input type="text" id="padding" class="padding-input-top" value="' + (defaultPaddingTop) + '" placeholder="Top">';
    options += '<input type="text" id="padding" class="padding-input-right" value="' + (defaultPaddingRight) + '" placeholder="Right">';
    options += '<i onclick="linkInputs(this);" class="fa-solid fa-link"></i>';
    options += '<input type="text" id="padding" class="padding-input-bottom" value="' + (defaultPaddingBottom) + '" placeholder="Bottom">';
    options += '<input type="text" id="padding" class="padding-input-left" value="' + (defaultPaddingLeft) + '" placeholder="Left">';
    options += '</div>';
    options += '<label for="border-radius">Border radius:</label>';
    options += '<div class="input-group">';
    options += '<input type="text" class="border-radius-topL" id="border-radius" value="' + (borderRadiusTopL) + '" placeholder="Top Left">';
    options += '<input type="text" class="border-radius-topR" id="border-radius" value="' + (borderRadiusTopR) + '" placeholder="Top Right">';
    options += '<i onclick="linkInputs(this);" class="fa-solid fa-link"></i>';
    options += '<input type="text" class="border-radius-botR" id="border-radius" value="' + (borderRadiusBotR) + '" placeholder="Bottom Left">';
    options += '<input type="text" class="border-radius-botL" id="border-radius" value="' + (borderRadiusBotL) + '" placeholder="Bottom Right">';
    options += '</div>';
    options += "<p>Height & Width</p>";
    options += '<input type="text" class="width-input" value="' + (defaultWidth ? defaultWidth[1] : '') + '" placeholder="Width">';
    options += '<input type="text" class="height-input" value="' + (defaultHeight ? defaultHeight[1] : '') + '" placeholder="Height">';
    options += "<p>Background settings</p>";
    options += '<label for="background-color">Background color:</label>';
    options += '<input type="color" class="background-color-input" id="background-color" value="' + defaultBackgroundColor + '" placeholder="Background Color">';
    options += '<label for="background-image">Background image:</label>';
    options += '<select class="background-image" id="background-image">';
    options += '<option value="none">Select image</option>';
    options += '</select>';
    options += '<label for="background-position">Background position:</label>';
    options += '<select class="background-position" id="background-position" value="' + (backgroundPosition ? backgroundPosition[1] : 'Center') + '">';
    options += '<option value="center">Center</option>';
    options += '<option value="top">Top</option>';
    options += '<option value="bottom">Bottom</option>';
    options += '<option value="left">Left</option>';
    options += '<option value="right">Right</option>';
    options += '</select>';
    options += '<label for="background-repeat">Background repeat:</label>';
    options += '<select class="background-repeat" id="background-repeat" value="' + backgroundRepeat + '">';
    options += '<option value="repeat">Repeat</option>';
    options += '<option value="no-repeat">No-repeat</option>';
    options += '</select>';
    options += '<label for="background-size">Background size:</label>';
    options += '<select class="background-size" id="background-repeat">';
    options += '<option value="cover">Cover</option>';
    options += '<option value="contain">Contain</option>';
    options += '<option value="fill">Fill</option>';
    options += '</select>';
    options += '<button class="parent-selection button">Select Parent</button>';
    options += '<button class="delete-block button">Delete Block</button>';
    $(".block-options").html(options);

    options = "";

    $(".margin-input-top").on("input", function () {
        $block.css("margin-top", $(this).val());
    });

    $(".margin-input-right").on("input", function () {
        $block.css("margin-right", $(this).val());
    });

    $(".margin-input-bottom").on("input", function () {
        $block.css("margin-bottom", $(this).val());
    });

    $(".margin-input-left").on("input", function () {
        $block.css("margin-left", $(this).val());
    });

    $(".padding-input-top").on("input", function () {
        $block.css("padding-top", $(this).val());
    });

    $(".padding-input-right").on("input", function () {
        $block.css("padding-right", $(this).val());
    });

    $(".padding-input-bottom").on("input", function () {
        $block.css("padding-bottom", $(this).val());
    });

    $(".padding-input-left").on("input", function () {
        $block.css("padding-left", $(this).val());
    });


    $(".width-input").on("input", function () {
        $block.css("width", $(this).val());
    });

    $(".height-input").on("input", function () {
        $block.css("height", $(this).val());
    });

    $(".background-color-input").on("input", function () {
        $block.css("background", $(this).val());
    });


    $(".border-radius-topL").on("input", function () {
        $block.css("border-top-left-radius", $(this).val());
    });
    $(".border-radius-topR").on("input", function () {
        $block.css("border-top-right-radius", $(this).val());
    });

    $(".border-radius-botR").on("input", function () {
        $block.css("border-bottom-right-radius", $(this).val());
    });
    $(".border-radius-botL").on("input", function () {
        $block.css("border-bottom-left-radius", $(this).val());
    });


    $(".parent-selection").on("click", function () {
        BlockOptions($block.parent());
    });

    $(".delete-block").on("click", function () {
        $selection = $block.closest().parent();
        $block.remove();
    });

    $(".background-position").change(function () {
        $block.css("background-position", $(this).val());
    });

    $(".background-repeat").change(function () {
        $block.css("background-repeat", $(this).val());
    });

    $(".background-size").change(function () {
        $block.css("background-size", $(this).val());
    });

    $.ajax({
        url: '../api.php',
        type: 'POST',
        data: {
            request: "media",
            data: 0,
            type: 'fetchAll',
        },
        error: function (xhr, status, error) {
            console.log(error);
        }
    }).done(function (response) {

        response = JSON.parse(response);

        if (response != null) {
            var updatedOptions = ""; // Create a new variable to store the updated options

            $.each(response, function (key, value) {
                if (value.file_type == "image") {
                    updatedOptions += "<option value='" + value.file + "'>" + value.name + "</option>";
                }
            });

            var imgLink = backgroundImage ? backgroundImage[1] : 'Select image';

            imgLink = imgLink.replace("url(", '');
            imgLink = imgLink.replace(")", '');

            $(".background-image").append(updatedOptions);
            // $(".background-image option[value=" + imgLink + "]").attr('selected', 'selected');
            $(".background-image").change(function () {

                var selectedValue = $(".background-image").val();
                $block.css("background-image", "url(" + selectedValue + ")");
            });

        }
    });
}


function linkInputs(caller) {
    const parent = $(caller).parent();

    
    var input = parent.find("input");


    if($(input[1]).attr("disabled") && $(caller).hasClass('active')) {
        $(caller).removeClass("active");

        $.each(input, function (index, item) {
            if (index !== 0) {
                $(item).removeAttr("disabled");
            }
        });
        $(input[0]).unbind("input.linkInputs");
        return;
    }

    $.each(input, function(index, item) {
        if (index !== 0) {
            $(item).attr("disabled", "disabled");
            $(item).val($(input[0]).val());
            $(item).trigger("input");
        }
    });

    $(caller).addClass("active");

    $(input[0]).bind("input.linkInputs", function () {
        $.each(input, function (index, item) {
            if (index !== 0) {
                $(item).val($(input[0]).val());
                $(item).trigger("input");
            }
        });
    });
}