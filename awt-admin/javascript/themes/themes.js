function getActiveTheme(callback)
{
    $.ajax({

        url: './jobs/themes.php',
        type: 'POST',
        data: {
            get_active_theme: 1
        },
        success: function(response) {
        },
        error: function(response) {
            console.log(response);
        }
    }).done(function(response) {
        callback(response);
    });
}