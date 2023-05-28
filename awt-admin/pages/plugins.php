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

<link rel="stylesheet" href="./css/plugins.css">
<script src="./javascript/plugins/installer.js"></script>
<script src="./javascript/plugins/pluginList.js"></script>
<section>
    <div class="upload hidden">
        <div class="upload-header">
            <h3>Add new plugin</h3>
            <button type="button" onclick="openClose('.upload');"><i class="fa-solid fa-circle-xmark"></i></button>
        </div>
        <div class="installer-container">
            <form method="post" enctype="multipart/form-data">
                <label for="file-upload" class="add-plugin"><p></p><img src="<?php echo HOSTNAME.'awt-data/icons/upload-solid.svg';?>" alt="icon"></label>
                <input type="file" id="file-upload" style="display: none;">
            </form>
            <div class="installer-jquery-response">
                <div class="installer-info-jquery-response" hidden>
                    <h3>Plugin info</h3>
                </div>
                <div class="installer-notice-jquery-response" hidden>
                    <h3>Installers Notice</h3>
                </div>
            </div>
        </div>
    </div>
</section>
<section>
    <button onclick="openClose('.upload');">Add new plugins</button>
</section>
<section>
    <div class="plugin-list">
        <div class="plugin" id="headers">
            <h4>Icon</h4>
            <h4>Name</h4>
            <h4>Description</h4>
            <h4>Action</h4>
        </div>
    </div>
</section>