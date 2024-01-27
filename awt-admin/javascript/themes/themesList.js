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

    const $dialog = $(".dialog");
    $(".overlay").toggleClass("hidden");
    $dialog.toggleClass("hidden");

    $dialog.empty();

    const header = $("<h1>Warning!</h1>");

    const par = $("<p>Removing this theme will also remove all prior customization made with it!</p>");
    const par2 = $("<p>This includes:</p>");
    const ol = $("<ol>");
    ol.append("<li>All settings that are bound to this theme.</li>");
    ol.append("<li>All theme pages that were customized.</li>");

    
    const cancelButton = $("<button>").addClass("button");
    cancelButton.attr("id", "green");
    cancelButton.text("Cancel");
    
    cancelButton.click(function() {
        $(".dialog").toggleClass("hidden");
        $(".overlay").toggleClass("hidden");
        $(".dialog").empty();
    });
    
    const deleteButton = $("<button>").addClass("button");
    deleteButton.attr("id", "red");
    deleteButton.text("Delete");
    
    deleteButton.click(function(){
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

        cancelButton.click();
    });
    
    const actions = $("<div>").addClass("actions");
    actions.append(cancelButton);
    actions.append(deleteButton);
    
    $dialog.append(header);
    $dialog.append(par);
    $dialog.append(par2);
    $dialog.append(ol);
    $dialog.append(actions);

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

