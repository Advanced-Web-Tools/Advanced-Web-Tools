function getBuiltInPages(callback)
{
    $.ajax({
        type: "POST",
        url: "./jobs/customize.php",
        data: {
            get_built_in_pages: 1
        },

        success: function (response) {
            callback(response);
        }
    });
}