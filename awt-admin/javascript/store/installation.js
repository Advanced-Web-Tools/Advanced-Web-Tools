function installPlugin(caller, link) {


    $(caller).html('Installing... <i class="fa-solid fa-spinner fa-spin-pulse"></i>');
    $.ajax({
        url: './jobs/store.php',
        type: 'POST',
        data: {
            installPlugin: link,
        },
        success: function() {
            
        },
        error: function () {
            console.log('AJAX request failed.');
        }
    }).done(function (response) {
        var $caller = $(caller);
        
        $caller.html('Reload required <i class="fa-solid fa-rotate-right"></i>');
        
        var newParagraph = $("<p>You may need to enable this plugin in Plugins page.</p>");
        $caller.after(newParagraph);
        
        $caller.on("click", function() {
            window.location.reload();
        });
    });
}

function installTheme(caller, link) {

    $(caller).html('Installing... <i class="fa-solid fa-spinner fa-spin-pulse"></i>');

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
        var $caller = $(caller);

        $caller.html('Reload required <i class="fa-solid fa-rotate-right"></i>');
        
        var newParagraph = $("<p>You may need to enable this theme in Themes page.</p>");
        $caller.after(newParagraph);
        
        $caller.on("click", function() {
            window.location.reload();
        });
    });
}