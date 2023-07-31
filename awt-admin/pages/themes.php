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
<link rel="stylesheet" href="./css/themes.css">
<section>
    <div class="upload hidden">
        <div class="upload-header">
            <h3>Add new theme</h3>
            <button type="button" onclick="openClose('.upload');"><i class="fa-solid fa-circle-xmark"></i></button>
        </div>
        <div class="installer-container">
            <form method="post" enctype="multipart/form-data">
                <label for="file-upload" class="add-theme"><p></p><img src="<?php echo HOSTNAME.'awt-data/icons/upload-solid.svg';?>" alt="icon"></label>
                <input type="file" id="file-upload" style="display: none;">
            </form>
            <div class="installer-jquery-response">
                <div class="installer-info-jquery-response" hidden>
                    <h3>Theme info</h3>
                </div>
                <div class="installer-notice-jquery-response" hidden>
                    <h3>Installers Notice</h3>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="add-themes">
    <input type="text" placeholder="Search in the store...">
    <button onclick="openClose('.upload');">Add new theme</button>
</section>
<div class="themesList">

</div>

<script src="./javascript/themes/themesList.js"></script>
<script src="./javascript/themes/installer.js"></script>
<script>
    $(document).ready(function () {
        getThemeList(".themesList", <?php echo "'".HOSTNAME."'";?>);
    }); 
</script>