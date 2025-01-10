import {Option} from "../../options/Option.js";
import {CreateColorInput} from "../../ui/ColorInputComp.js";
import {CreateContainerComponent} from "../../ui/Container.js";
import {CreateInputWithUnitComponent, CreateSelect} from "../../ui/Inputs.js";
import {NotPage} from "../conditions/DefaultConditions.js";
import {ExtractUnitAndValue, rgbToHex} from "../../options/Functions.js";
import {CreateButtonComponent, CreateButtonGroupComponent} from "../../ui/Buttons.js";

export let fontOptions = new Option();

const container = CreateContainerComponent("Text Options");

CreateInputWithUnitComponent(container, "number", "Font size", "Font size:", "font-size", "0", ["px", "pt", "rem", "em"])

CreateSelect(container, "font-weight", [100, 200, 300, 400, 500, 600, 700, 800, 900]);

container.querySelector("#font-weight").before(document.createElement("label").innerText = "Font weight:");

CreateColorInput(container, "Text color:", "font-color", "#000");

const btn_alignment_content = [
    "Left <i class=\"fa-solid fa-align-left\"></i>",
    "Center <i class=\"fa-solid fa-align-center\"></i>",
    "Right <i class=\"fa-solid fa-align-right\"></i>"
];

CreateButtonGroupComponent(container, "Text alignment:", true, btn_alignment_content, ["align_l", "align_c", "align_r"]);

fontOptions.addDrawable(0, container);

fontOptions.setCategory("Text");

fontOptions.setCallableCondition(function(block) {
    return NotPage(block);
});

fontOptions.attachFunction(0, function(block) {
    const [size_value, size_unit] = ExtractUnitAndValue(block, 'font-size');
    const current_weight = block.css("font-weight");
    const current_color = rgbToHex(block.css("color"));
    var current_alignment = block.css("text-align");

    const sizeInput = $("input#font-size");
    const sizeSelectUnit = $("select#font-size-unit");
    const weightInput = $("select#font-weight");
    const colorInput = $("input#font-color.color-input");

    sizeInput.val(size_value);
    sizeSelectUnit.val(size_unit);
    weightInput.val(current_weight);
    colorInput.val(current_color);

    const toggleAlignmentButton = (current_alignment) => {
        if(current_alignment === "start") {
            $("#align_l.btn_grouped").addClass("active");
            $("#align_c.btn_grouped").removeClass("active");
            $("#align_r.btn_grouped").removeClass("active");
        } else if (current_alignment === "center") {
            $("#align_l.btn_grouped").removeClass("active");
            $("#align_c.btn_grouped").addClass("active");
            $("#align_r.btn_grouped").removeClass("active");
        } else {
            $("#align_l.btn_grouped").removeClass("active");
            $("#align_c.btn_grouped").removeClass("active");
            $("#align_r.btn_grouped").addClass("active");
        }
    }

    toggleAlignmentButton(current_alignment);

    $("#align_l.btn_grouped").click((e) => {
        toggleAlignmentButton("start");
        block.css("text-align", "left");
    });

    $("#align_c.btn_grouped").click((e) => {
        toggleAlignmentButton("center");
        block.css("text-align", "center");
    });

    $("#align_r.btn_grouped").click((e) => {
        toggleAlignmentButton("right");
        block.css("text-align", "right");
    });

    sizeInput.on("input", (e) => {
        block.css("font-size", e.currentTarget.value + sizeSelectUnit.val());
    });

    sizeSelectUnit.on("change", (e) => {
        block.css("font-size", sizeInput.val() + e.currentTarget.value);
    });

    weightInput.on("change", (e) => {
        block.css("font-weight", e.currentTarget.value);
    });

    colorInput.on("input", (e) => {
        block.css("color", e.currentTarget.value);
    });
});

