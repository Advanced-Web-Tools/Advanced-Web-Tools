
function getThemeList(container, hostname) {
    var formData = new FormData();
    var $container = $(container);

    formData.append('get_themes', 1);

    $.ajax({
        url: './jobs/themes.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false
    }).done(function (data) {
        var response = JSON.parse(data);
        var html = ""
        $.each(response, function (index, value) {
            if(value.placeholder === null) {
                html += "<div class='card'><img src='"+hostname+"awt-data/icons/placeholder-image.jpg'/>"
            } else {
                html += "<div class='card'><img src='"+hostname+"awt-content/themes/"+value.name+"/"+value.placeholder+"'/>"
            }
            html += "<h3>"+value.name+"</h3>"
            html += "<p>"+value.description+"</p>"

            if(value.active === 1) {
                html += "<p>Version: "+value.version+" Selected: <input type='checkbox' checked disabled></input></p></div>"
            } else {
                var function_html = 'enableTheme("'+value.id+'","'+container+'","'+hostname+'")'
                html += "<p>Version: "+value.version+" Selected: <input type='checkbox' onclick='"+function_html+"'></input></p></div>"
            }


        });

        $container.html(html);

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

