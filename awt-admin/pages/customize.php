<?php

defined('DASHBOARD') or die("You should not do that..");
defined('ALL_CONFIG_LOADED') or die("An error has occured");

use admin\authentication;

$check = new authentication;

if (!$check->checkAuthentication()) {
    header("Location: ./login.php");
    exit();
}

?>
<link rel="stylesheet" href="./css/customize.css">
<script src="./javascript/customize/customize.js"></script>
<script src="./javascript/themes/themes.js"></script>

<section class='customize'>
    <div class="customize-theme shadow">
        <p class="theme-name">Theme</p>
        <div class='customize-wrapper'>
            <select id="page" class="select">
            </select>
            <a id='customize' href='<?php echo HOSTNAME . "/awt-admin/?page=Theme Editor" ?>' target='_blank'
                rel="norefer"><button type="button" class='button' id="green">Customize! <i
                        class="fa-solid fa-wand-magic-sparkles"></i></button>
        </div>

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

            $("#page").on('change', function(){
                $('#customize').attr('href', original_link + "&theme_page=" + $(this).val());
            });

        });
    });
</script>