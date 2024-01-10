function mediaOptions($block, defaultStyle) {

    var src = $block.attr("src");

    var options = "<p>Media options</p>";

    options += "<select id='selectMediaFile'>";

    options += "<option value='none'>Select Image</option>";

    fetchMediaFiles(options, function (updatedOptions) {
        options += updatedOptions;

        options += "</select>";

        $(".block-options").append(options);

        $("#selectMediaFile").change(function () {
            var selectedValue = $(this).val();
            $block.attr("src", selectedValue);
        });
    });
}

function fetchMediaFiles(options, callback) {
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

            // Call the callback with the updated options
            callback(updatedOptions);
        }
    });
}


function isMedia($block) {
    var allowedTags = ["audio", "video", "source", "track", "img", "source"];

    var tagName = $block.prop("tagName").toLowerCase();
    return allowedTags.includes(tagName);
}