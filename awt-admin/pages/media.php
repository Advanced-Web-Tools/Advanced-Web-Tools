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
<link rel="stylesheet" href="./css/media.css">
<script src="./javascript/media/createAlbum.js"></script>
<script src="./javascript/media/fetchAlbums.js"></script>
<script src="./javascript/media/deleteAlbum.js"></script>
<script src="./javascript/media/fetchMediaFiles.js"></script>
<script src="./javascript/media/addToAlbum.js"></script>

<div class="add-to-album-list hidden shadow">
</div>

<div class="media-files">
    <form action="./jobs/media.php" method="post" enctype="multipart/form-data">
        <input type="file" id="upload_button" name="uploadedFiles[]" multiple style="display: none;" />
        <label for="upload_button">
            <p class="button upload">Select Files</p>
        </label>
        <button type="submit" class="button">Upload</button>
    </form>
    <div class="group-actions hidden">
        <button type="button" class="button" onclick="addToAlbum('.add-to-album-list', '.media-list', selected, '<?php echo HOSTNAME; ?>')">Move to album</button>
        <button type="button" class="button" id="red" onclick="deleteMedia('.media-list', '<?php echo HOSTNAME; ?>')">Delete</button>
    </div>
    <div class="media-list">

    </div>
</div>
<div class="albums shadow">
    <div class="create-album">
        <input type="text" class="input album-name" minlength="5" maxlength="16" placeholder="Album name: Minimum length 5">
        <button type="button" class="button" onclick="createAlbum('.album-name', '.albums-list', '<?php echo HOSTNAME; ?>');">Create album</button>
    </div>
    <div class="albums-list">
        <div class="album">

        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        fetchAlbums(".albums-list", "<?php echo HOSTNAME; ?>");
        fetchMediaFiles(".media-list", "<?php echo HOSTNAME; ?>")
    });
</script>