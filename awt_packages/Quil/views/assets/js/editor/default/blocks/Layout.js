import { Block } from "../../blocks/Block.js";

export let flexible = new Block();
export let slider = new Block();
export let flexRow = new Block();
export let flexColumn = new Block();

const html = document.createElement("div");
html.classList.add("block");
html.style.display = "flex";
html.style.flexWrap = "wrap";
html.style.gap = "10px";
html.style.alignItems = "center";
html.style.justifyContent = "center";

const block = document.createElement("div");
block.classList.add("block");
block.style.width = "49%";

html.appendChild(block);
html.appendChild(block.cloneNode());

flexible.setName("Flexible");
flexible.setFaIcon("fa-solid fa-table-cells-large");
flexible.setCategory("Layout");
flexible.addBody(html);

const sliderHtml = document.createElement("div");
sliderHtml.classList.add("block");

sliderHtml.appendChild(block.cloneNode());
sliderHtml.appendChild(block.cloneNode());
sliderHtml.appendChild(block.cloneNode());
sliderHtml.appendChild(block.cloneNode());

sliderHtml.style.overflowX = "auto";
sliderHtml.style.display = "flex";
sliderHtml.style.flexDirection = "row";
sliderHtml.style.width = "100%";
sliderHtml.style.flexWrap = "nowrap";

slider.setName("Slider");
slider.setFaIcon("fa-solid fa-sliders");
slider.setCategory("Layout");
slider.addBody(sliderHtml);

const flexRowHtml = document.createElement("div");
flexRowHtml.classList.add("block");
flexRowHtml.style.display = "flex";
flexRowHtml.style.justifyContent = "space-between";

const leftBlock = block.cloneNode();
const rightBlock = block.cloneNode();

flexRowHtml.appendChild(leftBlock);
flexRowHtml.appendChild(rightBlock);

flexRow.setName("Flex Row");
flexRow.setFaIcon("fa-solid fa-bars");
flexRow.setCategory("Layout");
flexRow.addBody(flexRowHtml);

const flexColumnHtml = document.createElement("div");
flexColumnHtml.classList.add("block");
flexColumnHtml.style.display = "flex";
flexColumnHtml.style.flexDirection = "column"; // Set to column layout
flexColumnHtml.style.gap = "20px";

const topBlock = block.cloneNode();
const bottomBlock = block.cloneNode();

flexColumnHtml.appendChild(topBlock);
flexColumnHtml.appendChild(bottomBlock);

flexColumn.setName("Flex Column");
flexColumn.setFaIcon("fa-solid fa-bars");
flexColumn.setCategory("Layout");
flexColumn.addBody(flexColumnHtml);
