$(document).ready(function () {
    const container = $('.plugin-list');

    var formData = new FormData();
    formData.append('getList', 1);

    $.ajax({
        url: './jobs/plugins.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (data) {
            console.log(data);
        }
    }).done(function (data) {
        var response = JSON.parse(data);
        const HOSTNAME = "localhost/projectX/";
        $.each(response, function (index, value) {
            var plugin = container.find('.plugin#id' + value.id);
            if (!plugin.length) {
                var pluginList = '<div class="plugin" id="id' + value.id + '">';
                if (value.icon != null && value.icon != '') {
                    if (value.icon.endsWith('.svg')) {
                        pluginList += '<div class="plugin-header"> <img src="../awt-content/plugins/' + value.name + '/data/icons/' + value.icon + '" alt="icon" class="svg">';
                    } else {
                        pluginList += '<img src="../awt-content/plugins/' + value.name + '/data/icons/' + value.icon + '" alt="icon">';
                    }
                } else {
                    pluginList += '<img src="../awt-data/icons/placeholder-image.jpg" alt="icon">';
                }
                var disabled = '';
                if (value.xml.requiresAuthorization !== undefined) {
                    if (value.xml.requiresAuthorization === 'true' && value.status === 0) disabled = 'disabled';
                }
                pluginList += '<div class="plugin-header-name"><h2 class="name">' + value.name + ' v' + value.version + '</h2><p class="description">' + value.description + '</p></div></div><form class="action" method="post" action="./jobs/plugins.php?id=' + value.id + '&name=' + value.name + '">';
                if (value.status === 1) {
                    pluginList += '<button type="submit" value="0" name="action" id="red"' + disabled + '>Disable</button>';
                } else {
                    pluginList += '<button type="submit" value="1" name="action" id="green"' + disabled + '>Enable</button>';
                }
                if (value.xml.requiresAuthorization !== undefined) {
                    if (value.xml.requiresAuthorization === 'true') {
                        pluginList += '<button type="submit" value="authorize=' + value.xml.authorizationFile + '" name="action" id="green">Allow Database Access</button>';
                    } else {
                        pluginList += '<button type="submit" value="unauthorize=' + value.xml.authorizationFile + '" name="action" id="red">Disallow Database Access</button>';
                    }
                }
                pluginList += '<button type="submit" value="' + value.name + '=' + value.id + '" name="uninstall">Uninstall</button></form></div>';

                container.append(pluginList);
            }
        });
    });
});
