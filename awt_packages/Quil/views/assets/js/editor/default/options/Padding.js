import {createLockedInputGroup} from "../../ui/InputGroupComp.js";
import {Option} from "../../options/Option.js";
import {CreateContainerComponent} from "../../ui/Container.js";

export let paddingOptions = new Option();

const padding = CreateContainerComponent("Padding", false);

const sides = ["Top", "Right", "Bottom", "Left"];

createLockedInputGroup(padding, "Padding", sides);


paddingOptions.addDrawable(0, padding);

paddingOptions.attachFunction(0, function(block) {
    // Helper function to get padding value with a default of "0px"
    const getPaddingValue = (side) => {
        const value = block[0].style[`padding${side.charAt(0).toUpperCase() + side.slice(1)}`] || "0px";
        return value;
    };

    // Get padding values and units for each side
    const padding_top_value = getPaddingValue('top');
    const padding_top_unit = padding_top_value.replace(/[0-9.-]+/, ''); // Extract the unit (px, rem, etc.)
    const padding_top_number = parseFloat(padding_top_value) || 0; // Get the numeric part

    const padding_bottom_value = getPaddingValue('bottom');
    const padding_bottom_unit = padding_bottom_value.replace(/[0-9.-]+/, '');
    const padding_bottom_number = parseFloat(padding_bottom_value) || 0;

    const padding_right_value = getPaddingValue('right');
    const padding_right_unit = padding_right_value.replace(/[0-9.-]+/, '');
    const padding_right_number = parseFloat(padding_right_value) || 0;

    const padding_left_value = getPaddingValue('left');
    const padding_left_unit = padding_left_value.replace(/[0-9.-]+/, '');
    const padding_left_number = parseFloat(padding_left_value) || 0;

    // Set values to respective inputs and selects
    const top_input = $("input#padding-top");
    const top_unit = $("select#padding-top-unit");
    const bottom_input = $("input#padding-bottom");
    const bottom_unit = $("select#padding-bottom-unit");
    const right_input = $("input#padding-right");
    const right_unit = $("select#padding-right-unit");
    const left_input = $("input#padding-left");
    const left_unit = $("select#padding-left-unit");

    // Set the numeric value and unit for each padding side
    top_input.val(padding_top_number);
    top_unit.val(padding_top_unit);

    bottom_input.val(padding_bottom_number);
    bottom_unit.val(padding_bottom_unit);

    right_input.val(padding_right_number);
    right_unit.val(padding_right_unit);

    left_input.val(padding_left_number);
    left_unit.val(padding_left_unit);

    const initializePaddingInput = (side) => {
        const padding_value = getPaddingValue(side);
        const padding_unit = padding_value.replace(/[0-9.-]+/, ''); // Extract the unit (px, rem, etc.)
        const padding_number = parseFloat(padding_value) || 0; // Get the numeric part

        // Set initial values to respective inputs and selects
        const input = $(`input#padding-${side}`);
        const unit = $(`select#padding-${side}-unit`);

        input.val(padding_number);
        unit.val(padding_unit);

        // Attach event listener to the input
        input.on('input', function() {
            console.log(side);
            const value = input.val();
            const selectedUnit = unit.val();
            block[0].style[`padding-${side}`] = `${value}${selectedUnit}`;
        });

        // Attach event listener to the unit select
        unit.on('change', function() {
            const value = input.val();
            const selectedUnit = unit.val();
            block[0].style[`padding-${side}`] = `${value}${selectedUnit}`;
        });
    };

    // Initialize all padding sides
    ['top', 'bottom', 'left', 'right'].forEach(side => initializePaddingInput(side));
});
