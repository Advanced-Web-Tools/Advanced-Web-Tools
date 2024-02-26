<?php

defined('DASHBOARD') or die("You should not do that..");
defined('ALL_CONFIG_LOADED') or die("An error has occured");

use admin\{authentication, profiler};

$check = new authentication;

if (!$check->checkAuthentication()) {
  header("Location: ./login.php");
  exit();
}

$profiler = new profiler;


if(!$profiler->checkPermissions(1)) die("Insufficient permission to view this page!");

?>
<link rel="stylesheet" href="./css/customize.css">
<script src="./javascript/customize/customize.js"></script>
<script src="./javascript/themes/themes.js"></script>

<section class='customize'>
    <h2>Customize theme pages</h2>
    <div class="customize-theme shadow">
        <p class="theme-name">Theme</p>
        <div class='customize-wrapper'>
            <select id="page" class="select">
            </select>
            <a id='customize' href='<?php echo HOSTNAME . "/awt-admin/?page=Theme Editor" ?>' target='_blank'
                rel="norefer"><button type="button" class='button' id="green">Customize! <i
                        class="fa-solid fa-wand-magic-sparkles"></i></button></a>
        </div>
    </div>
    <h2>Customized theme pages</h2>
    <div class="customized-list">

    </div>
    <h2>Theme settings</h2>
    <div class="theme-settings shadow">
        <p class='placeholder'>This theme doesn't have any settings</p>
    </div>
</section>


<script>
    $(document).ready(function () {
        const original_link = $('#customize').attr('href');
        getActiveTheme(function (response) {
            const data = JSON.parse(response);
            $('.theme-name').text(data.name);
        });
        getBuiltInPages(function (data) {
            const pages = JSON.parse(data);

            $.each(pages, function (key, page) {
                if (page.name !== "custom") {
                    const html = $("<option value='" + page.name + "'>" + page.name + "</option>");
                    $("#page").append(html);
                }
            });

            $("#page").on('change', function () {
                $('#customize').attr('href', original_link + "&theme_page=" + $(this).val());
            });

        });

        getCustomizedPages(function (response) {
            const data = JSON.parse(response);
            $('.customized-list').html("<h3> This list is empty. Customize your first page to populate this. </h3>");

            if(data.length > 0) $('.customized-list').html(" ");

            $.each(data, function (key, page) {
                const html = $("<div>").addClass('customized-page').addClass('shadow');

                const page_name = $("<p>").addClass('page-name');

                const button = $("<button>").addClass('button');

                page_name.text(page.page_name);

                button.html('Revert changes <i class="fa-solid fa-rotate-right"></i>');
                button.attr("data-id", page.id);
                button.attr("id", "red");


                button.click(function(e) {
                    revertChanges(page.id, function(){
                        location.reload();
                    });
                });

                html.append(page_name);
                html.append(button);

                $('.customized-list').append(html);
            });

        });


        getThemeSettings(function(response){

            const data = JSON.parse(response);

            if(data) $('.theme-settings').html(" ");
            console.log(data);
            $.each(data, function (key, setting) { 
                const html = $("<div>");

                html.addClass("setting");

                const name = $("<p>");

                name.text(key.replace(/-/g, " ") + ": ");

                const input = $("<input>");

                input.attr("type", setting.type);

                input.val(setting.value);

                if(setting.type == "checkbox") {
                    if(setting.value == "true") input.attr('checked', 'true');
                    input.change(function() {
                        if(input.val() == 'true') {
                            input.val('false');
                        } else {
                            input.val('true');
                        }
                        
                    });
                }

                if(setting.placeholder) input.attr('placeholder', setting.placeholder);

                

                const button = $("<button>");
                button.addClass('button');
                button.text('Apply');

                button.click(function(){

                    const value = input.val();
                    

                    $.ajax({
                        type: "POST",
                        url: "./jobs/customize.php",
                        data: {
                            change_setting: key,
                            value: value
                        },

                        success: function (response) {
                            
                        }
                    });

                });

                const revert = $("<button>");
                revert.addClass('button');
                revert.attr("id", "red");
                if(setting.id) revert.attr('data-id', setting.id);
                revert.text('Revert Changes');

                revert.click(function() {
                    const id = revert.attr('data-id');
                    if(!id) return;

                    $.ajax({
                        type: "POST",
                        url: "./jobs/customize.php",
                        data: {
                            revert_setting: id,
                        },

                        success: function (response) {
                            location.reload();
                        }
                    });

                });


                html.append(name);
                html.append(input);
                html.append(button);
                html.append(revert);

                $('.theme-settings').append(html);

            });
        });

    });
</script>