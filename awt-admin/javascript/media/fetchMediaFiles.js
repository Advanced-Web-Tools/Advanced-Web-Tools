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
        if (data != null) {
            $.each(data, function (key, value) {
                html += "<div class='media'>";

                if (value.file_type == "image") {
                    html += "<img class='file' id='" + value.id + "' src='" + hostname + "awt-data/uploads/" + value.file_type + "/" + value.file + "' onclick='selectMedia(" + value.id + ")'/>";
                }

                if (value.file_type == "video") {
                    html += "<video class='file'id='" + value.id + "' controls width='260px'>";
                    html += '<source src="' + hostname + 'awt-data/uploads/' + value.file_type + '/' + value.file + '"></source>';
                    html += "</video>";
                    html += "<input type='checkbox' onclick='selectMedia(" + value.id + ")'/>"
                }

                if (value.file_type == "audio") {
                    html += "<video class='file' id='" + value.id + "' controls width='260px' poster='" + hostname + "/awt-data/icons/microphone.jpg'>";
                    html += '<source src="' + hostname + 'awt-data/uploads/' + value.file_type + '/' + value.file + '"></source>';
                    html += "</video>";
                    html += "<input type='checkbox' onclick='selectMedia(" + value.id + ")'/>"
                }

                html += "</div>";

            });
        }
        $(container).html(html);
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
        if (data != null) {
            $.each(data, function (key, value) {
                html += "<div class='media'>";

                if (value.file_type == "image") {
                    html += "<img class='file' id='" + value.id + "' src='" + hostname + "awt-data/uploads/" + value.file_type + "/" + value.file + "' onclick='selectMedia(" + value.id + ")'/>";
                }

                if (value.file_type == "video") {
                    html += "<video class='file'id='" + value.id + "' controls width='260px'>";
                    html += '<source src="' + hostname + 'awt-data/uploads/' + value.file_type + '/' + value.file + '"></source>';
                    html += "</video>";
                    html += "<input type='checkbox' onclick='selectMedia(" + value.id + ")'/>"
                }

                if (value.file_type == "audio") {
                    html += "<video class='file' id='" + value.id + "' controls width='260px' poster='" + hostname + "/awt-data/icons/microphone.jpg'>";
                    html += '<source src="' + hostname + 'awt-data/uploads/' + value.file_type + '/' + value.file + '"></source>';
                    html += "</video>";
                    html += "<input type='checkbox' onclick='selectMedia(" + value.id + ")'/>"
                }

                html += "</div>";

            });
        }
        $(container).html(html);
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

    console.log(selected);
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

    console.log(data);
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