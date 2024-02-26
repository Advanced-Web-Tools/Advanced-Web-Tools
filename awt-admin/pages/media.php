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


if(!$profiler->checkPermissions(2)) die("Insufficient permission to view this page!");

?>
<link rel="stylesheet" href="./css/media.css">
<link rel="stylesheet" href="./css/imageEditor.css">
<script src="./javascript/media/createAlbum.js"></script>
<script src="./javascript/media/fetchAlbums.js"></script>
<script src="./javascript/media/deleteAlbum.js"></script>
<script src="./javascript/media/fetchMediaFiles.js"></script>
<script src="./javascript/media/addToAlbum.js"></script>
<!-- Cropper -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"
    integrity="sha512-9KkIqdfN7ipEW6B6k+Aq20PV31bjODg4AA52W+tYtAE0jE0kMx49bjJ3FgvS56wzmyfMUHbQ4Km2b7l9+Y/+Eg=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css"
    integrity="sha512-hvNR0F/e2J7zPPfLC9auFe3/SE0yG4aJCOd/qxew74NN7eyiSKjr7xJJMu1Jy2wf7FXITpWS1E/RY8yzuXN7VA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="./javascript/media/imageEditor.js"></script>
<script src="https://unpkg.com/color.js@1.2.0/dist/color.js"></script>

<div class="add-to-album-list hidden shadow">
</div>

<div class="media-files">
    <form class="shadow" action="./jobs/media.php" method="post" enctype="multipart/form-data">
        <input type="file" id="upload_button" name="uploadedFiles[]" multiple style="display: none;" />
        <label for="upload_button">
            <p class="button upload">Select Files</p>
        </label>
        <button type="submit" class="button">Upload</button>
        <p id="number_of_files"></p>
    </form>
    <div class="group-actions shadow hidden">
        <button type="button" class="button"
            onclick="addToAlbum('.add-to-album-list', '.media-list', selected, '<?php echo HOSTNAME; ?>')">Move to
            album</button>
        <button type="button" class="button" id="red"
            onclick="deleteMedia('.media-list', '<?php echo HOSTNAME; ?>')">Delete</button>
    </div>
    <div class="media-list shadow">

    </div>
</div>
<div class="albums shadow">
    <div class="create-album">
        <input type="text" class="input album-name" minlength="5" maxlength="16"
            placeholder="Album name: Minimum length 5">
        <button type="button" class="button"
            onclick="createAlbum('.album-name', '.albums-list', '<?php echo HOSTNAME; ?>');">Create album</button>
    </div>
    <div class="albums-list">
        <div class="album">

        </div>
    </div>
</div>
<div class="overlay hidden"></div>
<div class="image-editor shadow hidden">
    <div class="editor-header">
        <i class="fa-solid fa-x"></i>
        <h3>Edit image</h3>
    </div>
    <div class="editor-container">
        <div class="image-container">
            <img src="" id="cropper">
        </div>
        <div class="editor-options">


            <div class="options-top">
                <div class="preview-container">
                    <div class="preview-cover">
                        <div class="preview" style="height: 9rem; width: 16rem;">
                        </div>
                    </div>
                </div>
                <div class="rotation-controls">
                    <button class="button" id="r-right"><i class="fa-solid fa-rotate-right"></i></button>
                    <button class="button" id="r-left"><i class="fa-solid fa-rotate-left"></i></button>
                    <button class="button" id="f-horizontal"><i class="fa-solid fa-right-left"></i></button>
                    <button class="button" id="f-vertical"><i class="fa-solid fa-arrow-down-up-across-line"></i></button>
                </div>
                <div class="move-img">
                    <h5>Move image:</h5>
                    <span><button class="button" id="m-up"><i class="fa-solid fa-up-long"></i></button></span>
                    <button class="button" id="m-left"><i class="fa-solid fa-left-long"></i></button>
                    <button class="button" id="m-right"><i class="fa-solid fa-right-long"></i></button>
                    <span><button class="button" id="m-down"><i class="fa-solid fa-down-long"></i></button></span>
                    
                </div>
            </div>

            <div class="info">
                <p>Image resolution: <span id="resolution"></span> </p>
                <hr>
                <p>Prominient colors: </p>
                <div class='pallete'>

                </div>
                <p>Average color: </p>
                <div class='color'>

                </div>
            </div>


            <button class='button save_image' id="green">Save changes <i class="fa-solid fa-floppy-disk"></i></button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        $("#upload_button").on("change", function () {

            $("#number_of_files").text("You have selected " + $(this)[0].files.length + " files for upload");

            if ($(this)[0].files.length == 0) $("#number_of_files").text(" ");
        })

        fetchAlbums(".albums-list", "<?php echo HOSTNAME; ?>");
        fetchMediaFiles(".media-list", "<?php echo HOSTNAME; ?>")

        $(".editor-header i").click(function () {
            closeImageEditor();
        });
    });
</script>