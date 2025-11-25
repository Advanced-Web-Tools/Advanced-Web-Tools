import { Option } from "../../options/Option.js";
import { CreateContainerComponent } from "../../ui/Container.js";
import { CreateSelect, CreateInputComponent, CreateInputWithUnitComponent } from "../../ui/Inputs.js";
import { CreateButtonGroupComponent } from "../../ui/Buttons.js";
import { NotPage } from "../conditions/DefaultConditions.js";

export let layoutOptions = new Option();

const layoutContainer = CreateContainerComponent("Layout", true);
layoutContainer.style.display = 'none';


const displayContainer = CreateContainerComponent("Display", false);
const [, displaySelectEl] = CreateSelect(displayContainer, 'layout-display', ['block', 'flex', 'grid', 'inline-block', 'none']);
displaySelectEl.before(document.createElement('label').innerText = 'Display:');
layoutContainer.appendChild(displayContainer);


const flexContainer = CreateContainerComponent("Flexbox Settings", false);
CreateButtonGroupComponent(flexContainer, "Direction:", true, ['<i class="fa-solid fa-arrow-right"></i> Row', '<i class="fa-solid fa-arrow-left"></i> Row Reverse', '<i class="fa-solid fa-arrow-down"></i> Column', '<i class="fa-solid fa-arrow-up"></i> Column Reverse'], ['flex-dir-row', 'flex-dir-row-reverse', 'flex-dir-column', 'flex-dir-column-reverse'], true, ['Row', 'Row Reverse', 'Column', 'Column Reverse']);

CreateButtonGroupComponent(flexContainer, "Justify Content:", true, 
    [
        '<i class="fa-solid fa-align-left"></i> Start', 
        '<i class="fa-solid fa-align-center"></i> Center', 
        '<i class="fa-solid fa-align-right"></i> End', 
        '<i class="fa-solid fa-align-justify"></i> Between', 
        '<i class="fa-solid fa-arrows-left-right"></i> Around', 
        '<i class="fa-solid fa-table-columns"></i> Evenly'
    ],
    [
        'justify-flex-start', 
        'justify-center', 
        'justify-flex-end', 
        'justify-space-between', 
        'justify-space-around', 
        'justify-space-evenly'
    ], 
    true, 
    ['Start', 'Center', 'End', 'Space Between', 'Space Around', 'Space Evenly']
);
CreateButtonGroupComponent(flexContainer, "Align Items:", true, ['<i class="fa-solid fa-align-left" style="transform: rotate(90deg);"></i> Start', '<i class="fa-solid fa-align-center" style="transform: rotate(90deg);"></i> Center', '<i class="fa-solid fa-align-right" style="transform: rotate(90deg);"></i> End', '<i class="fa-solid fa-grip-lines"></i> Stretch'], ['align-flex-start', 'align-center', 'align-flex-end', 'align-stretch'], true, ['Start', 'Center', 'End', 'Stretch']);


CreateButtonGroupComponent(flexContainer, "Wrap:", true, 
    [
        'No Wrap', 
        '<i class="fa-solid fa-turn-down"></i> Wrap', 
        '<i class="fa-solid fa-turn-up"></i> Reverse'
    ], 
    ['flex-nowrap', 'flex-wrap', 'flex-wrap-reverse'],
    true,
    ['No Wrap', 'Wrap', 'Wrap Reverse']
);
CreateInputWithUnitComponent(flexContainer, 'number', '10', 'Gap:', 'flex-gap', '10', ['px', 'em', 'rem', '%']);
layoutContainer.appendChild(flexContainer);


const gridContainer = CreateContainerComponent("Grid Settings", false);
CreateButtonGroupComponent(gridContainer, "Columns:", true, ['1', '2', '3', '4', 'Auto'], ['grid-cols-1', 'grid-cols-2', 'grid-cols-3', 'grid-cols-4', 'grid-cols-auto'], true, ['1 Column', '2 Columns', '3 Columns', '4 Columns', 'Auto Fit']);
CreateInputComponent(gridContainer, 'text', '1fr 1fr', 'Custom Columns:', 'grid-template-columns');
CreateInputWithUnitComponent(gridContainer, 'number', '10', 'Gap:', 'grid-gap', '10', ['px', 'em', 'rem', '%']);
layoutContainer.appendChild(gridContainer);


layoutOptions.addDrawable(0, layoutContainer);
layoutOptions.setCategory("Layout");
layoutOptions.setCallableCondition(block => NotPage(block));


layoutOptions.attachFunction(0, function(block) {
    layoutContainer.style.display = 'block';

    const getUnitAndValue = (styleValue) => {
        if (!styleValue || styleValue === 'normal') styleValue = '0px';
        const numeric = parseFloat(styleValue);
        const unit = styleValue.match(/[a-zA-Z%]+/)?.[0] || "px";
        return [isNaN(numeric) ? 0 : numeric, unit];
    };

    const handleButtonGroup = (prop, prefix, defaultValue, valueMap = null) => {
        const value = block.css(prop) || defaultValue;
        $(`button[id^='${prefix}-']`).removeClass('active');
        let currentId = `${prefix}-${value}`;
        if (valueMap) {
            currentId = Object.keys(valueMap).find(key => valueMap[key] === value) || null;
        }
        if(currentId) $(`#${currentId}`).addClass('active');

        $(`button[id^='${prefix}-']`).off('click').on('click', function() {
            const clickedId = $(this).attr('id');
            const newValue = valueMap ? valueMap[clickedId] : clickedId.replace(`${prefix}-`, '');
            block.css(prop, newValue);
            $(`button[id^='${prefix}-']`).removeClass('active');
            $(this).addClass('active');
            if (prop.startsWith('grid')) $(`#${prop}`).val(newValue);
        });
    };

    const currentDisplay = block.css('display');
    $(displaySelectEl).val(currentDisplay);
    $(flexContainer).toggle(currentDisplay === 'flex');
    $(gridContainer).toggle(currentDisplay === 'grid');

    $(displaySelectEl).off('change').on('change', function() {
        const newDisplay = $(this).val();
        block.css('display', newDisplay);
        $(flexContainer).toggle(newDisplay === 'flex');
        $(gridContainer).toggle(newDisplay === 'grid');
    });


    handleButtonGroup('flex-direction', 'flex-dir', 'row');
    handleButtonGroup('justify-content', 'justify', 'flex-start');
    handleButtonGroup('align-items', 'align', 'stretch');
    handleButtonGroup('flex-wrap', 'flex', 'nowrap');
    const flexGapInput = $('#flex-gap'), flexGapUnit = $('#flex-gap-unit');
    const [flexGapVal, flexUnitVal] = getUnitAndValue(block.css('gap'));
    flexGapInput.val(flexGapVal);
    flexGapUnit.val(flexUnitVal);
    const flexGapUpdate = () => block.css('gap', `${flexGapInput.val()}${flexGapUnit.val()}`);
    flexGapInput.off('input').on('input', flexGapUpdate);
    flexGapUnit.off('change').on('change', flexGapUpdate);


    const gridColPresets = { 'grid-cols-1': '1fr', 'grid-cols-2': '1fr 1fr', 'grid-cols-3': '1fr 1fr 1fr', 'grid-cols-4': '1fr 1fr 1fr 1fr', 'grid-cols-auto': 'repeat(auto-fit, minmax(250px, 1fr))' };
    handleButtonGroup('grid-template-columns', 'grid-cols', '1fr 1fr', gridColPresets);

    const gridColsInput = $('#grid-template-columns');
    gridColsInput.val(block.css('grid-template-columns'));
    gridColsInput.off('input').on('input', function() {
        const customValue = $(this).val();
        block.css('grid-template-columns', customValue);
        let presetFound = false;
        for (const id in gridColPresets) {
            if (gridColPresets[id] === customValue) {
                $(`button[id^='grid-cols-']`).removeClass('active');
                $(`#${id}`).addClass('active');
                presetFound = true;
                break;
            }
        }
        if (!presetFound) $(`button[id^='grid-cols-']`).removeClass('active');
    });

    const gridGapInput = $('#grid-gap'), gridGapUnit = $('#grid-gap-unit');
    const [gridGapVal, gridUnitVal] = getUnitAndValue(block.css('gap'));
    gridGapInput.val(gridGapVal);
    gridGapUnit.val(gridUnitVal);
    const gridGapUpdate = () => block.css('gap', `${gridGapInput.val()}${gridGapUnit.val()}`);
    gridGapInput.off('input').on('input', gridGapUpdate);
    gridGapUnit.off('change').on('change', gridGapUpdate);
});