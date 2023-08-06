function deleteAlbum(id, container, hostname) {
    $.ajax({
        url: './jobs/media.php',
        type: 'POST',
        data: {
            delete_album: id,
        },
        success: function (response) {
            console.log('AJAX request succeeded.');
            console.log(response);
        },
        error: function (xhr, status, error) {
            console.log('AJAX request failed.');
            console.log(error);
        }
    }).done(function (response) {
        fetchAlbums(container, hostname);
    });
}