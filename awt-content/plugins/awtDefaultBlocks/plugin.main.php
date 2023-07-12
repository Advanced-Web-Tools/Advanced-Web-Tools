<?php

function_exists("createBlockCollection") == true or die("Fatal error function createBlockCollection not found!");
function_exists("addBlock") == true or die("Fatal error function addBlock not found!");

$collection = "awtDefaultBlocks";
$defaultPath = PLUGINS.$collection.DIRECTORY_SEPARATOR."src".DIRECTORY_SEPARATOR;

createBlockCollection($collection);

$title = array("name" => "Title", "path" => $defaultPath."titleBlock.html");
$grid = array("name" => "Grid", "path" => $defaultPath."gridBlock.html");
$paragraph = array("name" => "Paragraph", "path" => $defaultPath."paragraphBlock.html");


addBlock($title, $collection);
addBlock($grid, $collection);
addBlock($paragraph, $collection);