import {Option} from "../../options/Option";
import {CreateColorInput} from "../../ui/ColorInputComp";
import {CreateContainerComponent} from "../../ui/Container";
import {rgbToHex} from "../../options/Functions";

export let backgroundOptions = new Option();

backgroundOptions.setCategory("Background");

const container = CreateContainerComponent("Background");

CreateColorInput(container, "Background color:", "background-color", "#fff");

backgroundOptions.addDrawable(0, container);

backgroundOptions.attachFunction(0, function(block) {
    let init = true;
    const current_bg_color = block.css("background-color");
    const input = $("input#background-color.color-input");

    input.val(rgbToHex(current_bg_color));
    input.css("background", current_bg_color);

    input.on("input", (e) => {
        if (init) return;
        block.css("background-color", input.val());
    });

    // Use setTimeout to delay the init flag reset
    setTimeout(() => {
        init = false; // Now we allow the input event to trigger
    }, 0); // Delay can be adjusted if needed
});
