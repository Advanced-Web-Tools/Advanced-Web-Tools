function addToAlbum(container1, container, selection, hostname) {
    if($(container1).hasClass("hidden")) {
        $(container1).removeClass("hidden");
    }  else {
        $(container1).addClass("hidden");
    }

    $.ajax({
        url: './jobs/media.php',
        type: 'POST',
        data: {
            fetch_albums: 1,
        },
        success: function (response) {
            console.log('AJAX request succeeded.');
        },
        error: function (xhr, status, error) {
            console.log('AJAX request failed.');
            console.log(error);
        }
    }).done(function(response){
        var data = JSON.parse(response);
        var html = "";
        if(data != null) {
            $.each(data, function(key, value) {
                console.log(selection);
                html += "<div class='album'>";
                html += "<p>"+value.name+"</p>";
                var params = "'"+key+"','"+container+"','"+hostname+"'" ;
                html += '<button class="button" onclick="moveToAlbum('+params+')">Move</button>';
                html += "</div>";
            });
        }
        var  params = "onclick=hideContainer('"+container1+"')";
        html += '<button type="button" class="button" '+params+'>Close</button>'
        $(container1).html(html);
    });
}

function hideContainer(container) {
    $(container).addClass("hidden");
}

function moveToAlbum(id, container, hostname) {

    var data = JSON.stringify(selected);
    selected = [];
    hideContainer(".add-to-album-list");
    hideContainer(".group-actions");
    $.ajax({
        url: './jobs/media.php',
        type: 'POST',
        data: {
            move_to_album: id,
            media: data
        },
        success: function (response) {
            console.log('AJAX request succeeded.');
        },
        error: function (xhr, status, error) {
            console.log('AJAX request failed.');
            console.log(error);
        }
    }).done(function(response){
        console.log(response);
        fetchMediaFiles(container, hostname);
    });
}