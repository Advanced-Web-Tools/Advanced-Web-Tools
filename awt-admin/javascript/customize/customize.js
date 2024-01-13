function getBuiltInPages(callback) {
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



function getCustomizedPages(callback) {
    $.ajax({
        type: "POST",
        url: "./jobs/customize.php",
        data: {
            fetch_custom: 1
        },

        success: function (response) {
            callback(response);
        }
    });
}

function revertChanges(id, callback)
{
    $.ajax({
        type: "POST",
        url: "./jobs/customize.php",
        data: {
            revert_changes: id
        },
        success: function (response) {
            callback(response);
        }
    });
}