$(document).ready(function () {
    var upload = $(".add-theme p");
    upload.html('Add new theme');
    $('#file-upload').on('change', function () {
        var formData = new FormData();
        formData.append('file', $('input[type=file]')[0].files[0]);
        formData.append('installer', 1);
        $.ajax({
            url: './jobs/themes.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                console.log('File upload successful!');
                console.log(data);
                var response = JSON.parse(data);
                var info = response.installer.info;
                var notice = response.installer.notice;

                // Build the HTML string to display the data
                var htmlStr = '';
                $('.installer-info-jquery-response p').remove();
                $('.installer-info-jquery-response').removeAttr('hidden');
                $('.installer-notice-jquery-response').removeAttr('hidden');
                for (var key in info) {
                    htmlStr += '<p><span class="capitalize">' + key + '</span>: ' + info[key] + '</p>';
                }

                // Add the HTML to the page
                $('.installer-info-jquery-response').append(htmlStr);

                var noticeHtml = '<p>All is good!</p>';
                $('.installer-notice-jquery-response p').remove();
                $('.upload #action').remove();
                if (response.installer.hasOwnProperty('notice')) {
                    noticeHtml = '';
                    for (var key in notice) {
                        noticeHtml += '<p><span class="capitalize">' + key + '</span>: ' + notice[key] + '</p>';
                    }
                }

                $('.installer-notice-jquery-response').append(noticeHtml || '<p>Everything is ok</p>');

                if (response.installer.hasOwnProperty('id')) {
                    var name = response.installer.info.name;
                    var id = response.installer.id;
                    var cancel = "<button class='install-button-cancel' value='cancel=" + id + "=" + name + "' id='red' onclick=installerAction(this);>Cancel</button>";
                    var install = "<button class='install-button' value='install=" + id + "=" + name + "' id='green' onclick=installerAction(this);>Install</button>";
                    $('.upload').append("<span id='action'>" + install + cancel + "</span>");
                    upload.html(name);
                }

            },
            error: function (xhr, status, error) {
                console.log(formData);
                console.log('File upload failed: ' + error);
            }
        });
    });
});

function installerAction(element) {
    var value = $(element).attr('value');
    console.log(value);
    $.ajax({
        url: './jobs/themes.php',
        type: 'POST',
        data: {
            'installerAction': value,
        },
        success: function (data) {
            console.log('Installer action successful!');
            console.log(data);
            var response = JSON.parse(data);
            var noticeHtml = "<p>" + response + "</p>";
            $('.installer-notice-jquery-response p').remove();
            $('.upload button').remove();
            $('.installer-notice-jquery-response').append(noticeHtml || '<p>Unexpected error</p>');

            setTimeout(function () {
                location.reload(true);
            }, 5000);
        },
        error: function (xhr, status, error) {
            console.log('Installer action failed: ' + error);
        }
    });
}

function openClose(element) {
    $(element).toggleClass('hidden');
    if(!$('.overlay').length) {
        $('body').append('<span class="overlay"></span>');
    } else {
        $('span.overlay').remove();
    }
}