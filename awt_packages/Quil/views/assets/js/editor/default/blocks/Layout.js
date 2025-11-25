import { Block } from "../../blocks/Block.js";
import { blocks } from "../../../main.js";


function createPlaceholderBlock(width = '100%', height = '50px') {
    const block = document.createElement("div");
    block.classList.add("block", "empty");
    block.style.width = width;
    block.style.minHeight = height;
    block.setAttribute('block-name', 'Inner Block');
    return block;
}

export let container;
export let flexbox;
export let grid;

export let twoColumns;
export let twoRows;
export let slider;


container = new Block();
const containerHtml = document.createElement("div");
containerHtml.classList.add("block");
containerHtml.style.padding = "10px";
containerHtml.appendChild(createPlaceholderBlock());
container.setName("Container");
container.setFaIcon("fa-solid fa-box");
container.setCategory("Layout");
container.addBody(containerHtml);
blocks.addBlock(container);

flexbox = new Block();
const flexboxHtml = document.createElement("div");
flexboxHtml.classList.add("block");
flexboxHtml.style.display = "flex";
flexboxHtml.style.flexWrap = "wrap";
flexboxHtml.style.gap = "10px";
flexboxHtml.style.padding = "10px";
flexboxHtml.appendChild(createPlaceholderBlock('48%'));
flexboxHtml.appendChild(createPlaceholderBlock('48%'));
flexbox.setName("Flexbox");
flexbox.setFaIcon("fa-solid fa-table-cells-large");
flexbox.setCategory("Layout");
flexbox.addBody(flexboxHtml);
blocks.addBlock(flexbox);

grid = new Block();
const gridHtml = document.createElement("div");
gridHtml.classList.add("block");
gridHtml.style.display = "grid";
gridHtml.style.gridTemplateColumns = "1fr 1fr"; // Podrazumevano 2 kolone
gridHtml.style.gap = "10px";
gridHtml.style.padding = "10px";
gridHtml.appendChild(createPlaceholderBlock());
gridHtml.appendChild(createPlaceholderBlock());
gridHtml.appendChild(createPlaceholderBlock());
gridHtml.appendChild(createPlaceholderBlock());
grid.setName("Grid");
grid.setFaIcon("fa-solid fa-grip");
grid.setCategory("Layout");
grid.addBody(gridHtml);
blocks.addBlock(grid);

twoColumns = new Block();
const twoColumnsHtml = document.createElement("div");
twoColumnsHtml.classList.add("block");
twoColumnsHtml.style.display = "flex";
twoColumnsHtml.style.justifyContent = "space-between";
twoColumnsHtml.style.gap = "10px";
twoColumnsHtml.style.padding = "10px";
twoColumnsHtml.appendChild(createPlaceholderBlock('48%'));
twoColumnsHtml.appendChild(createPlaceholderBlock('48%'));
twoColumns.setName("Two Columns");
twoColumns.setFaIcon("fa-solid fa-columns");
twoColumns.setCategory("Layout");
twoColumns.addBody(twoColumnsHtml);
blocks.addBlock(twoColumns);

twoRows = new Block();
const twoRowsHtml = document.createElement("div");
twoRowsHtml.classList.add("block");
twoRowsHtml.style.display = "flex";
twoRowsHtml.style.flexDirection = "column";
twoRowsHtml.style.gap = "10px";
twoRowsHtml.style.padding = "10px";
twoRowsHtml.appendChild(createPlaceholderBlock());
twoRowsHtml.appendChild(createPlaceholderBlock());
twoRows.setName("Two Rows");
twoRows.setFaIcon("fa-solid fa-bars");
twoRows.setCategory("Layout");
twoRows.addBody(twoRowsHtml);
blocks.addBlock(twoRows);

slider = new Block();
const sliderHtml = document.createElement("div");
sliderHtml.classList.add("block");
sliderHtml.style.display = "flex";
sliderHtml.style.overflowX = "auto";
sliderHtml.style.flexWrap = "nowrap";
sliderHtml.style.gap = "10px";
sliderHtml.style.padding = "10px";
sliderHtml.appendChild(createPlaceholderBlock('40%', '80px'));
sliderHtml.appendChild(createPlaceholderBlock('40%', '80px'));
sliderHtml.appendChild(createPlaceholderBlock('40%', '80px'));
slider.setName("Slider");
slider.setFaIcon("fa-solid fa-sliders");
slider.setCategory("Layout");
slider.addBody(sliderHtml);
blocks.addBlock(slider);