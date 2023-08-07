function fetchAlbums(container, hostname) {
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
        html += "<div class='album'>";
        html += "<p>Everything</p>";
        html += '<button class="button" id="green" onclick="fetchMediaFilesAlbum(\'all\', \'.media-list\', \'' + hostname + '\')">Show</button>';
        html += "</div>";
        if(data != null) {
            $.each(data, function(key, value) {
                html += "<div class='album'>";
                html += "<p>"+value.name+"</p>";
                html += "<span>";
                var params = "'"+key+"','.media-list','"+hostname+"'";
                html += '<button class="button" id="green" onclick="fetchMediaFilesAlbum('+params+')">Show</button>';
                var params = "'"+key+"','"+container+"','"+hostname+"'";
                html += '<button class="button" id="red" onclick="deleteAlbum('+params+')">Delete</button>';
                html += "</span>";
                html += "</div>";
            });
        }
        $(container).html(html);
    });
}