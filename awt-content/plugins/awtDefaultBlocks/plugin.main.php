<?php

function_exists("createBlockCollection") == true or die("Fatal error function createBlockCollection not found!");
function_exists("addBlock") == true or die("Fatal error function addBlock not found!");

$collection = "awtDefaultBlocks";
$defaultPath = PLUGINS.$collection.DIRECTORY_SEPARATOR."src".DIRECTORY_SEPARATOR;
$collection = "Default Blocks";
createBlockCollection($collection);

$title = array("name" => "Title", "path" => $defaultPath."titleBlock.html");
$paragraph = array("name" => "Paragraph", "path" => $defaultPath."paragraphBlock.html");
$listBlock = array("name" => "List", "path" => $defaultPath."listBlock.html");
$spacer = array("name" => "Space", "path" => $defaultPath."spacerBlock.html");
$emptyBlock = array("name" => "Empty Block", "path" => $defaultPath."emptyblock.html");
$grid = array("name" => "Grid", "path" => $defaultPath."gridBlock.html");
$imageBlock = array("name" => "Image", "path" => $defaultPath."mediaBlock.html");

addBlock($title, $collection);
addBlock($paragraph, $collection);
addBlock($listBlock, $collection);
addBlock($spacer, $collection);
addBlock($emptyBlock, $collection);
addBlock($grid, $collection);
addBlock($imageBlock, $collection);