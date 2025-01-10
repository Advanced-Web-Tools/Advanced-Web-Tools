import {Option} from "../../../Quil/views/assets/js/editor/options/Option.js";
import {CreateContainerComponent} from "../../../Quil/views/assets/js/editor/ui/Container.js";
import {CreateButtonComponent, CreateButtonGroupComponent} from "../../../Quil/views/assets/js/editor/ui/Buttons.js";
import {RequestMedia} from "./RequestMedia.js";
import {CreateGallerySelectComponent} from "./CreateGallerySelectComponent.js";
import {CloseHelper, PopulateHelper} from "../../../Dashboard/js/ui/Helper.js";
import {createLockedInputGroup} from "../../../Quil/views/assets/js/editor/ui/InputGroupComp.js";

export let ImageOption = new Option();
export let VideoOption = new Option();
export let BackgroundImage = new Option();

ImageOption.setCategory("Media");
VideoOption.setCategory("Media");
BackgroundImage.setCategory("Default");
BackgroundImage.setCategory("Background");



const image_container = CreateContainerComponent("Media options");
const video_container = CreateContainerComponent("Video options");
const image_button = CreateContainerComponent("", false);
const video_button = CreateContainerComponent("", false);

var label = document.createElement("label");
label.textContent = "Select image:";
image_button.appendChild(label);

CreateButtonComponent(image_button, "Select image <i class=\"fa-regular fa-image\"></i>", "image-select", 'secondary', true, "Select image to display.");
image_container.appendChild(image_button);

CreateButtonComponent(video_button, "Select video <i class=\"fa-regular fa-video\"></i>", "image-select", 'secondary', true, "Select video.");
image_container.appendChild(video_button);

ImageOption.addDrawable(0, image_container);
ImageOption.attachFunction(0, function (block) {

    $("button#image-select.btn_secondary").on("click", (e) => {

        e.stopPropagation();

        console.log("click");

        RequestMedia((data) => {
            const gallery = CreateGallerySelectComponent(data);

            $(gallery).find("img").each((index, image) => {
                $(image).click((e) => {
                    const name = $(image).attr("data-name");
                    const id = $(image).attr("data-id");
                    const src = $(image).attr("src");

                    const target = block.find("img")[0];

                    $(target).attr("data-name", name);
                    $(target).attr("data-id", id);
                    $(target).attr("alt", name);
                    $(target).attr("src", src);
                    CloseHelper(e, document.querySelector(".helper"));
                });
            });

            $(gallery).find(".video-selector").each((index, video) => {
                $(video).on("click", (e) => {
                    e.preventDefault();
                    console.log("click");
                    const src = $(e.currentTarget).find("source")[0];

                    block.find("source").each((index, source) => {
                        $(source).attr("src", $(src).attr("src"));
                    });

                    CloseHelper(e, document.querySelector(".helper"));
                });
            });

            PopulateHelper("Media Files", gallery);
        });
    });
});

const bgContainer = CreateContainerComponent("", false);

label = document.createElement("label");
label.textContent = "Background:";
bgContainer.appendChild(label);

CreateButtonComponent(bgContainer, "Select image <i class=\"fa-regular fa-image\"></i>", "background-select", 'secondary', true, "Select background image.");
CreateButtonGroupComponent(bgContainer, "Background size:", true, [
    "Auto <i class=\"fa-solid fa-expand\"></i>",
    "Cover <i class=\"fa-solid fa-arrows-alt\"></i>",
    "Contain <i class=\"fa-solid fa-compress\"></i>"
], ["background-auto", "background-cover", "background-contain"]);

CreateButtonGroupComponent(bgContainer, "Background position:", true, [
    "Top <i class=\"fa-solid fa-arrow-up\"></i>",
    "Left <i class=\"fa-solid fa-arrow-left\"></i>",
    "Center <i class=\"fa-solid fa-dot-circle\"></i>",
    "Right <i class=\"fa-solid fa-arrow-right\"></i>",
    "Bottom <i class=\"fa-solid fa-arrow-down\"></i>",
    "Manual <i class=\"fas fa-pen-nib \"></i>",
], ["background-top", "background-left", "background-center", "background-right", "background-bottom", "position-manual"]);


createLockedInputGroup(bgContainer, "Background position",["Top", "Right", "Bottom", "Left"], "number", 0, 100, ["px", "%"]);

BackgroundImage.addDrawable(0, bgContainer);
BackgroundImage.attachFunction(0, function (block) {
    const initialBgSize = block.css("background-size");
    const initialBgPosition = block.css("background-position");
    const positions = ["auto", "top", "left", "center", "right", "bottom"]

    if (!initialBgSize || initialBgSize === 'none') {
        $("button#background-auto, button#background-cover, button#background-contain").removeClass("active");
    } else {
        if (initialBgSize === "auto") {
            $("#background-auto").addClass("active");
        } else if (initialBgSize === "cover") {
            $("#background-cover").addClass("active");
        } else if (initialBgSize === "contain") {
            $("#background-contain").addClass("active");
        }
    }

    if (!initialBgPosition || initialBgPosition === 'none' || !positions.includes(initialBgPosition)) {
        $("button#background-top, button#background-left, button#background-center, button#background-right, button#background-bottom").removeClass("active");
        $("button#position-manual").addClass("active");
    } else {
        if (initialBgPosition.includes("top")) {
            $("#background-top").addClass("active");
        } else if (initialBgPosition.includes("left")) {
            $("#background-left").addClass("active");
        } else if (initialBgPosition.includes("center")) {
            $("#background-center").addClass("active");
        } else if (initialBgPosition.includes("right")) {
            $("#background-right").addClass("active");
        } else if (initialBgPosition.includes("bottom")) {
            $("#background-bottom").addClass("active");
        }
    }

    $("button#background-select.btn_secondary").on("click", (e) => {
        e.stopPropagation();
        console.log("click");
        RequestMedia((data) => {
            const gallery = CreateGallerySelectComponent(data);
            $(gallery).find("img").each((index, image) => {
                $(image).click((e) => {
                    block.css("background-image", "url('" + $(image).attr("src") + "')");
                    CloseHelper(e, document.querySelector(".helper"));
                });
            });
            PopulateHelper("Media Files", gallery);
        });
    });

    function setBackgroundSize(sizeClass, buttonId) {
        block.css("background-size", sizeClass);
        $("button#background-auto, button#background-cover, button#background-contain").removeClass("active"); // Remove active class from all size buttons
        $(buttonId).addClass("active"); // Add active class to the clicked button
    }

    $("button#background-auto").on("click", (e) => {
        setBackgroundSize("auto", "#background-auto");
    });

    $("button#background-cover").on("click", (e) => {
        setBackgroundSize("cover", "#background-cover");
    });

    $("button#background-contain").on("click", (e) => {
        setBackgroundSize("contain", "#background-contain");
    });

    function setBackgroundPosition(positionClass, buttonId) {
        block.css("background-position", positionClass);
        $("button#background-top, button#background-left, button#background-center, button#background-right, button#background-bottom").removeClass("active"); // Remove active class from all position buttons
        $(buttonId).addClass("active");
    }

    $("button#background-top").on("click", (e) => {
        setBackgroundPosition("top", "#background-top");
    });

    $("button#background-left").on("click", (e) => {
        setBackgroundPosition("left", "#background-left");
    });

    $("button#background-center").on("click", (e) => {
        setBackgroundPosition("center", "#background-center");
    });

    $("button#background-right").on("click", (e) => {
        setBackgroundPosition("right", "#background-right");
    });

    $("button#background-bottom").on("click", (e) => {
        setBackgroundPosition("bottom", "#background-bottom");
    });
});




