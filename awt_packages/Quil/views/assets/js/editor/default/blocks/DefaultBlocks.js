import {Block} from "../../blocks/Block.js"
import {blocks} from "../../../main.js";
import {container, flexbox, grid, twoRows, twoColumns, slider} from "./Layout.js";
import {table} from "./Table.js";

const h1 = new Block();
h1.setName("Heading");
h1.setCategory("Default");
h1.setFaIcon("fa-solid fa-heading");
h1.addBody("<h1 class='block' style='font-size: 2rem;'>Heading</h1>");

const h2 = new Block();
h2.setName("Subheading");
h2.setCategory("Default");
h2.setFaIcon("fa-solid fa-heading");
h2.addBody("<h2 class='block' style='font-size: 1.8rem;'>Subheading</h2>");


const h3 = new Block();
h3.setName("Sub-subheading");
h3.setCategory("Default");
h3.setFaIcon("fa-solid fa-heading");
h3.addBody("<h3 class='block' style='font-size: 1.5rem;'>Sub-subheading</h3>");

const p = new Block();
p.setName("Paragraph");
p.setCategory("Default");
p.setFaIcon("fa-solid fa-paragraph");
p.addBody("<p class='block' style='font-size: 1rem;'>This is a paragraph.</p>");

const a = new Block();
a.setName("Hyperlink");
a.setCategory("Default");
a.setFaIcon("fa-solid fa-link");
a.addBody("<a class='block' style='font-size: 1rem;' href=\"#\" rel='nofollow'>Hyperlink</a>");

const div = new Block();
div.setName("Container");
div.setCategory("Default");
div.setFaIcon("fa-solid fa-cube");
div.addBody("<div class='block'></div>");

// Button Block
const button = new Block();
button.setName("Button");
button.setCategory("Default");
button.setFaIcon("fa-solid fa-square");

const buttonContent = new Block();
buttonContent.addBody("<span class='block'>Content</span>");
buttonContent.setName("Button content");

button.addBody("<button class='block btn btn-primary'>" + buttonContent.getBody()[0].outerHTML + "</button>");


// List Block
const ul = new Block();
ul.setName("Unordered List");
ul.setCategory("Default");
ul.setFaIcon("fa-solid fa-list-ul");
ul.addBody("<ul class='block'><li block-name='List Item' class='block'>Item 1</li><li block-name='List Item' class='block'>Item 2</li><li block-name='List Item' class='block'>Item 3</li></ul>");

const ol = new Block();
ol.setName("Ordered List");
ol.setCategory("Default");
ol.setFaIcon("fa-solid fa-list-ol");
ol.addBody("<ol class='block'><li block-name='List Item' class='block'>Item 1</li><li block-name='List Item' class='block'>Item 2</li><li block-name='List Item' class='block'>Item 3</li></ol>");

const li = new Block();
li.setName("List Item");
li.setCategory("Default");
li.setFaIcon("fa-solid fa-font");
li.addBody("<li class='block'>Item</li>")

// Blockquote Block
const blockquote = new Block();
blockquote.setName("Blockquote");
blockquote.setCategory("Default");
blockquote.setFaIcon("fa-solid fa-quote-left");
blockquote.addBody("<blockquote class='block'>This is a quote.</blockquote>");

// Code Block
const code = new Block();
code.setName("Code Block");
code.setCategory("Default");
code.setFaIcon("fa-solid fa-code");
code.addBody("<pre class='block'><code>console.log('Code block');</code></pre>");

// Horizontal Line Block
const hr = new Block();
hr.setName("Horizontal Line");
hr.setCategory("Default");
hr.setFaIcon("fa-solid fa-minus");
hr.addBody("<hr class='block' />");


blocks.addBlock(h1);
blocks.addBlock(h2);
blocks.addBlock(h3);
blocks.addBlock(p);
blocks.addBlock(a);
blocks.addBlock(div);
blocks.addBlock(button);
blocks.addBlock(ul);
blocks.addBlock(ol);
blocks.addBlock(li);
blocks.addBlock(blockquote);
blocks.addBlock(code);
blocks.addBlock(hr);
blocks.addBlock(flexbox);
blocks.addBlock(grid);
blocks.addBlock(twoColumns);
blocks.addBlock(twoRows);
blocks.addBlock(slider);
blocks.addBlock(table);

