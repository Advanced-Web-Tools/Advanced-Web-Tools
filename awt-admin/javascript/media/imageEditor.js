
var cropper;


async function openEditor(caller) {
    const img_id = $(caller).attr("data-id");
    $(".overlay").removeClass("hidden");
    $(".image-editor").removeClass("hidden");

    const image_src = $("#" + img_id).attr("src");
    let colorSpace = $(".info .pallete");
    colorSpace.html(" ");

    const img = new Image();

    img.src = image_src;

    img.onload = function () {
        $(".info #resolution").text(this.width + 'x' + this.height);
    }
    
    setImageEditor(image_src);

    await colorjs.prominent(img, {amount: 10}).then(colors => {
        colors.forEach(element => {
            var color = $("<span>");
            color.addClass("colorBlock");
            color.css("background-color", "rgb(" + element[0] + "," + element[1] + "," + element[2] + ")");
            colorSpace.append(color);
        });
    });

    await colorjs.average(img).then(colors => {
        $(".info .color").css("background-color", "rgb(" + colors[0] + "," + colors[1] + "," + colors[2] + ")");
    });

}


function setImageEditor(img_src) {
    $(".editor-container img#cropper").attr("src", img_src);
    const image = document.getElementById('cropper');

    var options = {

        dragMode: 'move',

        preview: '.preview',
    
        viewMode: 2,
    
        modal: false,
    
        background: false,

        aspect_ratio: 16 / 9,

        ready(event) {


            $("#r-right").off("click");


            $("#r-left").off("click");


            $("#f-horizontal").off("click");


            $("#f-vertical").off("click");


            $("#m-up").off("click");

            $("#m-down").off("click");

            $("#m-right").off("click");

            $("#m-left").off("click");


            $("#r-right").click(function() {
                cropper.rotate(45)
            });

            $("#r-left").click(function() {
                cropper.rotate(-45)
            });

            var flipX = -1
            var flipY = -1

            $("#f-horizontal").click(function() {
                cropper.scale(flipX, 1)
                flipX = -flipX
            });

            $("#f-vertical").click(function() {
                cropper.scale(1, flipY)
                flipY = -flipY
            });


            $("#m-left").click(function() {
                cropper.move(-1, 0)
            });
            
            $("#m-up").click(function() {
                cropper.move(0, -1)
            });

            $("#m-down").click(function() {
                cropper.move(0, 1)
            });
            
            $("#m-right").click(function() {
                cropper.move(1, 0)
            });


            $(".save_image").off("click");
            $(".save_image").on("click", function () {
                cropper.getCroppedCanvas().toBlob(function (blob) {

                    var formData = new FormData($("#uploadForm")[0]);
                    formData.append("uploadedFiles[]", blob, "image.png");

                    $.ajax({
                        url: "./jobs/media.php",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            window.location.reload();        fetchMediaFiles(".media-list", "<?php echo HOSTNAME; ?>")
                        },
                        error: function (xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                });
            });
        }
    }


    cropper = new Cropper(image, options);
}

function closeImageEditor() {
    $(".overlay").addClass("hidden");
    $(".image-editor").addClass("hidden");
    cropper.destroy();
}


