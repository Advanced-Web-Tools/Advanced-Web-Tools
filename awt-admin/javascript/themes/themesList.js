function getThemeList(container, hostname) {
    var formData = new FormData();
    let $container = $(container);
    let glob_hostname = hostname;
    formData.append('get_themes', 1);

    $.ajax({
        url: './jobs/themes.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false
    }).done(function (data) {
        var response = JSON.parse(data);
        var $htmlContainer = $("<div></div>");

        $.each(response, function (index, value) {
            var $card = $("<div class='card shadow'></div>");

            if (value.placeholder === null) {
                $card.append("<img src='" + hostname + "awt-data/icons/placeholder-image.jpg'/>");
            } else {
                $card.append("<img src='" + hostname + "awt-content/themes/" + value.name + "/" + value.placeholder + "'/>");
            }

            $card.append("<h3>" + value.name + "</h3>");
            $card.append("<p>" + value.description + "</p>");
            
            $deleteButton = $("<button>").addClass("button");
            $deleteButton.attr("id", "red");
            $deleteButton.html('<i class="fa-solid fa-trash"></i>');
            $deleteButton.attr("onclick", "deleteTheme('" + value.id + "', '" + value.name + "', '" + container + "', '" + glob_hostname + "')")

            if(value.active !== 1) $card.append($deleteButton);




            if (value.active === 1) {
                $card.append("<p>Version: " + value.version + " Selected: <input type='checkbox' checked disabled></input></p>");
            } else {
                $card.append("<p>Version: " + value.version + " Selected: <input type='checkbox' onclick='enableTheme(\"" + value.id + "\", \"" + container + "\", \"" + hostname + "\")'></input></p>");
            }

            $htmlContainer.append($card);
        });

        $container.html($htmlContainer.html());
    });
}

function deleteTheme(id, name, container, hostname) {

    $.ajax({
        url: './jobs/themes.php',
        type: 'POST',
        data: {
            delete_theme: id,
            name: name
        },
    }).done(function (data) {
        console.log(data)
        getThemeList(container, hostname)
    });
}


function enableTheme(id, container, hostname) {
    var formData = new FormData();
    formData.append('enable_theme', id);
    var $container = $(container);
    $.ajax({
        url: './jobs/themes.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false
    }).done(function (data) {
        getThemeList(container, hostname)
        console.log(data);
    });

}

