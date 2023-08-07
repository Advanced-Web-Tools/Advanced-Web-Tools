function createAlbum(input, container, hostname) {

    var name = $(input).val();

    if (name.length >= 5) {
        $.ajax({
            url: './jobs/media.php',
            type: 'POST',
            data: {
                create_album: name,
            },
            success: function (response) {
                console.log('AJAX request succeeded.');
                console.log(response);
            },
            error: function (xhr, status, error) {
                console.log('AJAX request failed.');
                console.log(error);
            }
        }).done(function () {
            fetchAlbums(container, hostname);
        });
    }
}