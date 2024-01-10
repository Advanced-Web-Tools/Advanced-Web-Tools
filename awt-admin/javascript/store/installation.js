function installPlugin(caller, link) {
    $.ajax({
        url: './jobs/store.php',
        type: 'POST',
        data: {
            installPlugin: link,
        },
        success: function() {
            $(caller).html('Installing... <i class="fa-solid fa-spinner fa-spin-pulse"></i>');
        },
        error: function () {
            console.log('AJAX request failed.');
        }
    }).done(function (response) {
        $(caller).html('Reload required <i class="fa-solid fa-rotate-right"></i>');
        $(caller).attr("onclick", "window.location.reload()");
    });
}

function installTheme(caller, link) {
    $.ajax({
        url: './jobs/store.php',
        type: 'POST',
        data: {
            installTheme: link,
        },
        success: function() {
            $(caller).html('Installing... <i class="fa-solid fa-spinner fa-spin-pulse"></i>');
        },
        error: function () {
            console.log('AJAX request failed.');
        }
    }).done(function (response) {
        $(caller).html('Reload required <i class="fa-solid fa-rotate-right"></i>');
        $(caller).attr("onclick", "window.location.reload()");
    });
}