function createCardItem(json) {
    var container = $("<a class='store-card shadow' href='?page=Store&viewStore=" + json.title + "'></a>");
    var html = $("<div class='store-info'><div class='wrapper'></div></div>");

    if (json.icon) {
        html.find(".wrapper").append("<img src='" + json.icon + "'>");
        if (json.icon.search(".svg") !== -1) html.find(".wrapper").find("img").addClass("svg");
    }

    html.find(".wrapper").append("<h2 class='store-title'>" + json.title + "</h2>");
    html.find(".wrapper").append("<p class='store-description'>" + json.description + "</p>");
    html.find(".wrapper").append("<p class='store-publisher'> Created by: " + json.username + "</p>");

    if (json.image) {
        container.css({ "background-image": "url('" + json.image + "')" });
        html.addClass("background");
    }

    container.append(html);
    return container[0].outerHTML;
}

function createCards(json) {
    var html = "";

    $.each(json, function (index, value) {
        html += createCardItem(value);
    });

    return html;
}

function createCardsCategory(json, type) {
    var html = "<h2 class='type'>" + type + "s</h2>";
    var firstCardAdded = false;

    $.each(json, function (index, value) {
        if (value.type == type) {
            if (!firstCardAdded) {
                html += createCardItem(value);
                $(html).find('.store-card.shadow').addClass("big");
                firstCardAdded = true;
            } else {
                html += createCardItem(value);
            }
        }
    });

    return html;
}

function loadStore(container, store) {
    $.ajax({
        url: './jobs/store.php',
        type: 'POST',
        data: {
            searchStore: store,
            type: "*",
        },
        error: function () {
            console.log('AJAX request failed.');
        }
    }).done(function (response) {
        const json = JSON.parse(JSON.parse(response));
        $(container).html(createStorePage(json));
    });
}

function createStorePage(json) {

    $("body").css("background", "#fff");
    json = json[0];
    var container = $("<div class='store-container'></div>");
    var header = $("<div class='store-header'></div>");
    var headerTitle = $("<h2></h2>").text(json.title).addClass("title");
    var headerPublisher = $("<p></p>").text("Created by: " + json.username).addClass("publisher");
    var icon = $("<img>").attr("src", json.icon).addClass("icon");
    var headerImage = $("<div class='header-image'></div>").css("background-image", "url('" + json.image + "')");
    var installButton = $("<button></button>").html("Install <i class=\"fa-solid fa-download\"></i>").addClass("button");

    if (json.installed) {
        installButton.html("Installed <i class=\"fa-solid fa-check\"></i>").attr("id", "green").attr("disabled", "disabled");
    } else {
        if (json.type === "theme") {
            installButton.attr("onclick", "installTheme(this, '" + json.path + "')");
        } else {
            installButton.attr("onclick", "installPlugin(this, '" + json.path + "')");
        }
    }

    var infoContainer = $("<div class='header-info'></div>");


    if (json.icon.search(".svg") !== -1) icon.addClass("svg");

    infoContainer.append(icon);
    infoContainer.append(headerTitle);
    infoContainer.append(headerPublisher);
    infoContainer.append(installButton);

    if (!json.image) headerImage.css("border-radius", "inherit");
    header.append(headerImage);
    header.append(infoContainer);

    var gallery = $("<div></div>").addClass("store-gallery");
    var galleryTitle = $("<h3></h3>").addClass('gallery-title').text("Screenshots");
    gallery.append(galleryTitle);

    var galleryImage = $("<img>").attr("src", json.image).addClass("gallery-item");

    gallery.append(galleryImage);

    var description = $("<div></div>").addClass("store-description");

    var descriptionTitle = $("<h3></h3>").addClass('description-title').text("About this " + json.type);

    var descriptionContent = $("<p></p>").addClass("description").text(json.description);

    var descriptionPackageInfo = $("<ul></ul>").addClass("package-info");
    descriptionPackageInfo.append("<h4>Package info</h4>");
    descriptionPackageInfo.append("<li>Package name: " + json.name + "</li>");
    descriptionPackageInfo.append("<li>Version: " + json.version + "</li>");
    descriptionPackageInfo.append("<li>Minimum AWT Version: " + json.awt_version + "</li>");

    console.log(json);

    description.append(descriptionTitle);
    description.append(descriptionContent);
    description.append(descriptionPackageInfo);


    container.append(header);
    container.append(gallery);
    container.append(description);

    return container[0].outerHTML;
}


function checkIfInstalled() {

}