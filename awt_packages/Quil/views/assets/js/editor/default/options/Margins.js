import {createLockedInputGroup} from "../../ui/InputGroupComp.js";
import {Option} from "../../options/Option.js";
import {NotPage} from "../conditions/DefaultConditions.js";
import {ExtractUnitAndValue} from "../../options/Functions.js";
import {CreateContainerComponent} from "../../ui/Container.js";
import {CreateSelect} from "../../ui/Inputs.js";

export let marginOptions = new Option();

const margin = CreateContainerComponent("Margin", false);
let margOptions = ["manual", "auto", "inherit", "initial"];

const sides = ["Top", "Right", "Bottom", "Left"];
createLockedInputGroup(margin, "Margin", sides);

const t_parent = $(margin).find("#margin-top-unit").parent().parent();
const r_parent = $(margin).find("#margin-right-unit").parent().parent();
const b_parent = $(margin).find("#margin-bottom-unit").parent().parent();
const l_parent = $(margin).find("#margin-left-unit").parent().parent();

CreateSelect(t_parent[0], "margin-top-option", margOptions);
CreateSelect(r_parent[0], "margin-right-option", margOptions);
CreateSelect(b_parent[0], "margin-bottom-option", margOptions);
CreateSelect(l_parent[0], "margin-left-option", margOptions);

marginOptions.addDrawable(0, margin);

marginOptions.setCallableCondition(function (block) {
    return NotPage(block);
});

marginOptions.attachFunction(0, function (block) {

    const [margin_top_number, margin_top_unit] = ExtractUnitAndValue(block, "margin-top");
    const [margin_bottom_number, margin_bottom_unit] = ExtractUnitAndValue(block, "margin-bottom");
    const [margin_right_number, margin_right_unit] = ExtractUnitAndValue(block, "margin-right");
    const [margin_left_number, margin_left_unit] = ExtractUnitAndValue(block, "margin-left");

    const top_input = $("input#margin-top");
    const top_unit = $("select#margin-top-unit");
    const bottom_input = $("input#margin-bottom");
    const bottom_unit = $("select#margin-bottom-unit");
    const right_input = $("input#margin-right");
    const right_unit = $("select#margin-right-unit");
    const left_input = $("input#margin-left");
    const left_unit = $("select#margin-left-unit");

    top_input.val(margin_top_number);
    top_unit.val(margin_top_unit);

    bottom_input.val(margin_bottom_number);
    bottom_unit.val(margin_bottom_unit);

    right_input.val(margin_right_number);
    right_unit.val(margin_right_unit);

    left_input.val(margin_left_number);
    left_unit.val(margin_left_unit);

    const initializeMarginInput = (side) => {
        const [margin_number, margin_unit] = ExtractUnitAndValue(block, "margin-" + side);

        const input = $(`input#margin-${side}`);
        const unit = $(`select#margin-${side}-unit`);

        input.val(margin_number);
        unit.val(margin_unit);

        input.on('input', function () {
            const value = input.val();
            const selectedUnit = unit.val();
            block[0].style[`margin-${side}`] = `${value}${selectedUnit}`;
        });

        unit.on('change', function () {
            const value = input.val();
            const selectedUnit = unit.val();
            block[0].style[`margin-${side}`] = `${value}${selectedUnit}`;
        });

        $(`#margin-${side}-option`).on("change", function() {
            const value = $(`#margin-${side}-option`).val();

            if(value !== "manual") {
                input.attr("disabled", "disabled");
                unit.attr("disabled", "disabled");
                block.css(`margin-${side}`, value);
            } else {
                input[0].removeAttribute("disabled");
                unit[0].removeAttribute("disabled");
                block.css(`margin-${side}`, input.val() + unit.val());
            }
        });


        if(margOptions.includes(block[0].style[`margin-${side}`])) {
            $(`#margin-${side}-option`).val(block[0].style[`margin-${side}`]).trigger("change");
        } else {
            $(`#margin-${side}-option`).val("manual").trigger("change");
        }

    };

    ['top', 'bottom', 'left', 'right'].forEach(side => initializeMarginInput(side));
});

