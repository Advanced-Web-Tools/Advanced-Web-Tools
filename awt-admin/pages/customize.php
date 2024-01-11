<?php

defined('DASHBOARD') or  die("You should not do that..");
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
        <a href='<?php echo HOSTNAME . "/awt-admin/?page=Theme Editor"?>' target='_blank' rel="norefer" ><button type="button" class='button' id="green">Customize! <i class="fa-solid fa-wand-magic-sparkles"></i></button>
    </div>
</section>


<script>
    $(document).ready(function () {
        getActiveTheme(function(response){
            const data = JSON.parse(response);
            $('.theme-name').text(data.name);
        });    
    });
</script>