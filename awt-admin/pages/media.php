<?php

defined('DASHBOARD') or die("You should not do that..");
defined('ALL_CONFIG_LOADED') or die("An error has occured");


?>


<form action="./jobs/media.php" method="post" enctype="multipart/form-data">
    <input type="file" name="uploadedFiles[]" multiple>
    <button type="submit">Upload</button>
</form>

