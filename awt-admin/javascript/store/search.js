function searchPackage(input, container, type) {
    const search = $(input).val();

    if (!search) return;

    console.log(search);

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

        console.log(response);

        jsonArray = JSON.parse(JSON.parse(response));
        console.log(jsonArray);
    
        $(container).html("");
    
        $.each(jsonArray, function (index, item) {
            var name = item.name;
    
            var html = "<div class='search-item'>";
            html += "<p>" + name + "</p>";

            if(type == "plugin") {
                var button = "<button class='button' onclick='installPlugin(this, \""+ item.path +"\");' >Install <i class=\"fa-solid fa-download\"></i></button>";
            } else {
                var button = "<button class='button' onclick='installTheme(this, \""+ item.path +"\");' >Install <i class=\"fa-solid fa-download\"></i></button>";
            }

            if(item.installed && item.installed === true) {
                button = "<button class='button' id='green' disabled >Installed <i class=\"fa-solid fa-check\"></i></button>";
            }

            html += button;
            html += "</div>";
    
            $(container).append(html);
        });
    });
    
}


function installPlugin(caller, link) {
    $.ajax({
        url: './jobs/store.php',
        type: 'POST',
        data: {
            installPlugin: link,
        },
        success: function() {
            $(caller).html('Installing... <i class="fa-solid fa-spinner fa-spin-pulse"></i>');
        },
        error: function () {
            console.log('AJAX request failed.');
        }
    }).done(function (response) {
        $(caller).html('Reload required <i class="fa-solid fa-rotate-right"></i>');
        $(caller).attr("onclick", "window.location.reload()");
    });
}

function installTheme(caller, link) {
    $.ajax({
        url: './jobs/store.php',
        type: 'POST',
        data: {
            installTheme: link,
        },
        success: function() {
            $(caller).html('Installing... <i class="fa-solid fa-spinner fa-spin-pulse"></i>');
        },
        error: function () {
            console.log('AJAX request failed.');
        }
    }).done(function (response) {
        $(caller).html('Reload required <i class="fa-solid fa-rotate-right"></i>');
        $(caller).attr("onclick", "window.location.reload()");
    });
}
