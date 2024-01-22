function fetchMediaFiles(container, hostname) {
    $.ajax({
        url: './jobs/media.php',
        type: 'POST',
        data: {
            get_media: 1,
        },
        error: function (xhr, status, error) {
            console.log(error);
        }
    }).done(function (response) {
        var data = JSON.parse(response);
        var html = "";
        $(container).html(" ");
        if (data.length != 0) {
            $.each(data, function (key, value) {
                html = $("<div class='media'>");

                if (value.file_type == "image") {
                    html.append("<img class='file' id='" + value.id + "' src='" + value.file + "' onclick='selectMedia(" + value.id + ")'/>");
                    const button = $('<button class="button crop-img" data-id="' + value.id + '"><i class="fa-solid fa-wand-magic-sparkles"></i></button>');
                    
                    button.on("click", function(e) {
                        openEditor(this);
                    });

                    html.append(button);
                }

                if (value.file_type == "video") {
                    const video = $("<video class='file'id='" + value.id + "' controls width='260px'>");
                    video.append('<source src="' + value.file + '"></source>');
                    html.append(video);
                    html.append("<input type='checkbox' onclick='selectMedia(" + value.id + ")'/>");
                }

                if (value.file_type == "audio") {
                    const video = $("<video class='file'id='" + value.id + "' controls width='260px' poster='" + hostname + "/awt-data/icons/microphone.jpg'>");
                    video.append('<source src="' + value.file + '"></source>');
                    html.append(video);
                    html.append("<input type='checkbox' onclick='selectMedia(" + value.id + ")'/>");
                }
                $(container).append(html);
            });

        } else {
            $(container).html("<p>Upload you first files to show them here</p>");
        }

    });
}

function fetchMediaFilesAlbum(id, container, hostname) {
    $.ajax({
        url: './jobs/media.php',
        type: 'POST',
        data: {
            get_media_from_album: id,
        },
        error: function (xhr, status, error) {
            console.log(error);
        }
    }).done(function (response) {
        var data = JSON.parse(response);
        var html = "";
        $(container).html(" ");
        if (data.length != 0) {
            $.each(data, function (key, value) {
                html = $("<div class='media'>");

                if (value.file_type == "image") {
                    html.append("<img class='file' id='" + value.id + "' src='" + value.file + "' onclick='selectMedia(" + value.id + ")'/>");
                    const button = $('<button class="button crop-img" data-id="' + value.id + '"><i class="fa-solid fa-wand-magic-sparkles"></i></button>');
                    
                    button.on("click", function(e) {
                        openEditor(this);
                    });

                    html.append(button);
                }

                if (value.file_type == "video") {
                    const video = $("<video class='file'id='" + value.id + "' controls width='260px'>");
                    video.append('<source src="' + value.file + '"></source>');
                    html.append(video);
                    html.append("<input type='checkbox' onclick='selectMedia(" + value.id + ")'/>");
                }

                if (value.file_type == "audio") {
                    const video = $("<video class='file'id='" + value.id + "' controls width='260px' poster='" + hostname + "/awt-data/icons/microphone.jpg'>");
                    video.append('<source src="' + value.file + '"></source>');
                    html.append(video);
                    html.append("<input type='checkbox' onclick='selectMedia(" + value.id + ")'/>");
                }
                $(container).append(html);
            });
        } else {
            $(container).html("<p>Upload you first files to show them here</p>");
        }
    });
}

var selected = Array();

function selectMedia(id) {
    if (selected.includes(id)) {
        selected = arrayRemove(selected, id)
    } else {
        selected.push(id);
    }
    $("#" + id).toggleClass("selected");

    if (selected.length != 0) {
        $(".group-actions").removeClass("hidden");
    } else {
        if (!$(".group-actions").hasClass("hidden")) $(".group-actions").addClass("hidden");
    }
}

function arrayRemove(arr, value) {

    return arr.filter(function (returned) {
        return returned != value;
    });
}

function deleteMedia(container, hostname) {

    var data = JSON.stringify(selected);

    selected = [];

    $(".group-actions").addClass("hidden");

    $.ajax({
        url: './jobs/media.php',
        type: 'POST',
        data: {
            delete_media: data,
        },
        success: function (response) {
            fetchMediaFiles(container, hostname);
        },
        error: function (xhr, status, error) {
            console.log(error);
        }
    })
}