function searchPackage(input, container, type) {
    const search = $(input).val();

    if (!search) return;

    $.ajax({
        url: './jobs/store.php',
        type: 'POST',
        data: {
            search: search,
            type: type,
        },
        error: function () {
            console.log('AJAX request failed.');
        }
    }).done(function (response) {

        jsonArray = JSON.parse(JSON.parse(response));

        $(container).html("");

        $.each(jsonArray, function (index, item) {
            var name = item.name;

            var html = "<div class='search-item'>";
            html += "<p>" + name + "</p>";

            if (type == "plugin") {
                var button = "<button class='button' onclick='installPlugin(this, \"" + item.path + "\");' >Install <i class=\"fa-solid fa-download\"></i></button>";
            } else {
                var button = "<button class='button' onclick='installTheme(this, \"" + item.path + "\");' >Install <i class=\"fa-solid fa-download\"></i></button>";
            }

            if (item.installed && item.installed === true) {
                button = "<button class='button' id='green' disabled >Installed <i class=\"fa-solid fa-check\"></i></button>";
            }

            html += button;
            html += "</div>";

            $(container).append(html);
        });
    });

}

function searchStore(input, select, container) {
    input = $(input).val();

    select = $(select).val();

    $.ajax({
        url: './jobs/store.php',
        type: 'POST',
        data: {
            searchStore: input,
            type: select,
        },
        error: function () {
            console.log('AJAX request failed.');
        }
    }).done(function (response) {
        const json = JSON.parse(JSON.parse(response));
        const html = createCards(json);
        $(container).html(html);
    });

}

function loadPage(container) {
    $.ajax({
        url: './jobs/store.php',
        type: 'POST',
        data: {
            searchStore: "",
            type: "*",
        },
        error: function () {
            console.log('AJAX request failed.');
        }
    }).done(function (response) {
        const json = JSON.parse(JSON.parse(response));
        var html = "<div class='container'>";
        html = createCardsCategory(json, "theme");
        html += createCardsCategory(json, "plugin");
        $(container).html(html);
    });
}
