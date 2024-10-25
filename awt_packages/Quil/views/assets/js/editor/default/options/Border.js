import {createLockedInputGroup} from "../../ui/InputGroupComp";
import {Option} from "../../options/Option";
import {CreateColorInput} from "../../ui/ColorInputComp";
import {NotPage} from "../conditions/DefaultConditions";
import {ExtractUnitAndValue, rgbToHex} from "../../options/Functions";
import {CreateContainerComponent} from "../../ui/Container";
import {CreateSelect} from "../../ui/Inputs";

export let borderOptions = new Option();

const sides = ["Top", "Right", "Bottom", "Left"];
const styles = ["solid", "dashed", "dotted", "double", "groove", "ridge", "inset", "outset", "none"];
const radius = ["Top Left", "Top Right", "Bottom Right", "Bottom Left"];

const borderRadius = CreateContainerComponent("Border radius", false);
const borderWidth = CreateContainerComponent("Border options", false);
const borderStyle = CreateContainerComponent("Border style", false);
const borderColor = CreateContainerComponent("Border color", false);

createLockedInputGroup(borderRadius, "Border Radius", radius);
createLockedInputGroup(borderWidth, "Border width", sides, "number", 0, 100, ["px", "em", "rem"]);
CreateSelect(borderStyle, "border-style", styles);
CreateColorInput(borderColor, "Border color:", "border-color");

borderStyle.querySelector("#border-style").before(document.createElement("label").innerText = "Border style:");

borderOptions.addDrawable(0, borderRadius);
borderOptions.addDrawable(1, borderWidth);
borderOptions.addDrawable(2, borderStyle);
borderOptions.addDrawable(3, borderColor);


borderOptions.setCallableCondition(function(block) {
    return NotPage(block);
});

borderOptions.setCategory("Border");

borderOptions.attachFunction(0, function (block) {
    const toCamelCase = (side) => side.replace(/-([a-z])/g, (g) => g[1].toUpperCase());

    const updateBorderRadius = (input, unit, side) => {
        const value = input.val();
        const selectedUnit = unit.val();
        // Apply CSS using jQuery's .css() method
        block.css(`border-${side}-radius`, `${value}${selectedUnit}`);
    };

    const initializeBorderRadiusInput = (side) => {
        const [radiusValue, radiusUnit] = ExtractUnitAndValue(block, `border-${side}-radius`);

        const input = $(`input#border-radius-${side}`);
        const unit = $(`select#border-radius-${side}-unit`);

        input.val(radiusValue);
        unit.val(radiusUnit);

        const handleChange = () => updateBorderRadius(input, unit, side);
        input.on('input', handleChange);
        unit.on('change', handleChange);
    };

    // Initialize for each corner
    ['top-right', 'top-left', 'bottom-right', 'bottom-left'].forEach(initializeBorderRadiusInput);
});
borderOptions.attachFunction(1, function (block) {
    const toCamelCase = (side) => side.replace(/-([a-z])/g, (g) => g[1].toUpperCase());

    const updateBorderThickness = (input, unit, side) => {
        const value = input.val();
        const selectedUnit = unit.val();
        block.css(`border-${side}-width`, `${value}${selectedUnit}`);
    };

    const initializeBorderThicknessInput = (side) => {
        const [borderThicknessValue, borderThicknessUnit] = ExtractUnitAndValue(block, `border-${side}-width`);

        const input = $(`input#border-thickness-${side}`);
        const unit = $(`select#border-thickness-${side}-unit`);

        input.val(borderThicknessValue);
        unit.val(borderThicknessUnit);
        const handleChange = () => updateBorderThickness(input, unit, side);
        input.on('input', handleChange);
        unit.on('change', handleChange);
    };

    ['top', 'right', 'bottom', 'left'].forEach(initializeBorderThicknessInput);
});
borderOptions.attachFunction(2, function (block) {
    const current_style = block.css("border-style");

    const select = $("select#border-style");

    select.find("option[value='" + current_style + "']").prop("selected", true);

    select.change((e) => {
        block.css("border-style", select.val());
    });
});
borderOptions.attachFunction(3, function (block) {

    const current_color = block.css("border-color");

    const hexColor = current_color.startsWith('rgb') ? rgbToHex(current_color) : current_color;

    const input = $("input#border-color.color-input");
    input.css("background-color", current_color);
    input.val(hexColor);

    input.on("input", (e) => {
        block.css("border-color", input.val());
    });

});






