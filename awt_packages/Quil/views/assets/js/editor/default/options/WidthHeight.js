import {Option} from "../../options/Option.js";
import {createLockedInputGroup} from "../../ui/InputGroupComp.js";
import {NotPage} from "../conditions/DefaultConditions.js";
import {CreateContainerComponent} from "../../ui/Container.js";
import {ExtractUnitAndValue} from "../../options/Functions.js";
import {CreateSelect} from "../../ui/Inputs.js";

export let dimensionOptions = new Option();
const dimensions  = CreateContainerComponent("", false);

const dimOptions = ["manual", "auto", "fit-content", "inherit", "initial"];

createLockedInputGroup(dimensions, "Dimensions", ["Width", "Height"], "number", 0, 1000, ["px", "pt", "%"]);

const w_parent = dimensions.querySelector("#dimensions-width").parentNode;
const h_parent = dimensions.querySelector("#dimensions-height").parentNode;


const w_options = document.createElement("div");
w_options.classList.add("input-unit");

const w_label = document.createElement("label");
w_label.innerText = "Width option:";
w_options.appendChild(w_label);

const h_options = document.createElement("div");
h_options.classList.add("input-unit");

const h_label = document.createElement("label");
h_label.innerText = "Height option:";
h_options.appendChild(h_label);

CreateSelect(w_options, "width_options", dimOptions);
CreateSelect(h_options, "height_options", dimOptions);

$(w_parent).after(w_options);
$(h_parent).after(h_options);

dimensionOptions.addDrawable(0, dimensions);

dimensionOptions.setCallableCondition(function(block) {
    return NotPage(block);
});

dimensionOptions.attachFunction(0, function(block) {

    const [current_width, current_w_unit] = ExtractUnitAndValue(block, "width");

    const [current_height, current_h_unit] = ExtractUnitAndValue(block, "height");

    const width_input = $("input#dimensions-width");
    const width_unit_select = $("select#dimensions-width-unit");
    const height_input = $("input#dimensions-height");
    const height_unit_select = $("select#dimensions-height-unit");

    width_input.val(current_width);
    width_unit_select.val(current_w_unit);

    height_input.val(current_height);
    height_unit_select.val(current_h_unit);

    const initializeDimensionInput = (dimension) => {
        const input = $(`input#dimensions-${dimension}`);
        const unit = $(`select#dimensions-${dimension}-unit`);
        const option = $(`select#${dimension}_options`);

        option.on("change", function() {
            const value = option.val();
            if(value !== "manual") {
                input.attr("disabled", "disabled");
                unit.attr("disabled", "disabled");
                block.css(dimension, value);

            } else {
                input[0].removeAttribute("disabled");
                unit[0].removeAttribute("disabled");
            }
        });

        const current_w_css = block[0].style['width'];
        const current_h_css = block[0].style['height'];

        if (dimOptions.includes(current_w_css)) {
            $("#width_options").val(current_w_css).trigger("change");
            console.log(current_w_css);
        } else {
            $("#width_options").val("manual").trigger("change");
        }

        if (dimOptions.includes(current_h_css)) {
            $("#height_options").val(current_h_css).trigger("change");
        } else {
            $("#height_options").val("manual").trigger("change");
        }

        input.on('input', function() {
            const value = input.val();
            const selectedUnit = unit.val();
            block[0].style[dimension] = `${value}${selectedUnit}`;
        });

        unit.on('change', function() {
            const value = input.val();
            const selectedUnit = unit.val();
            block[0].style[dimension] = `${value}${selectedUnit}`;
        });
    };

    initializeDimensionInput('width');
    initializeDimensionInput('height');
});