function imageOptions($block, defaultStyle) {

    var options = "<p>Image options</p>";

    const button = $("<button>");
    button.addClass("button");
    button.text("Select image");


    button.on("click", button => {
        fetchImages($block, function ($block, images) {
            createDialog($block, images);
        });
    });

    $(".block-options").append(options);
    $(".block-options").append(button);
}

function fetchImages($block, callback) {
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
            var images = []; 

            $.each(response, function (key, value) {
                if (value.file_type == "image") {
                    images.push(value);
                }
            });
            
            callback($block, images);
        }
    });
}


function createDialog($block, images) {
    const dialog = $(".dialog");
    dialog.toggleClass("active");

    dialog.css("height", "90%");
    dialog.css("width", "90%");
    const content = $(".dialog .content");

    content.html(" ");

    images.forEach(function(value, index) {

        console.log(value);

        const image = $("<img>");
        image.attr("src", value.file);

        image.css({
            "width": "220px",
            "height" : "220px",
            "border-radius": "5px",
            "object-fit": "cover",
        });

        image.on("click", image => {
            $block.attr("src", value.file);
            dialog.toggleClass("active");
        });

        content.css("justify-content", "flex-start");

        content.append(image);
    });

}


function isImage($block) {

    var allowedTags = ["img"];

    var tagName = $block.prop("tagName").toLowerCase();

    if(allowedTags.includes(tagName)) return true;
    return false;
}

var option = new BlockOption(isImage, imageOptions);
blockOptions.push(option);