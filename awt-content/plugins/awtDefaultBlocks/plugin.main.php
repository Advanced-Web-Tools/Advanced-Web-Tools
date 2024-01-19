<?php

use blocks\{block, BlockCollection, BlockOptions};

$collection = "awtDefaultBlocks";
$defaultPath = PLUGINS.$collection.DIRECTORY_SEPARATOR."src".DIRECTORY_SEPARATOR;

$urlToIcons = HOSTNAME . "/awt-content/plugins/" . $collection . "/data/icons/";

$blocks = [
    new block('Title', $defaultPath . "titleBlock.html", $urlToIcons . "title.png"),
    new block('List', $defaultPath . "listBlock.html", $urlToIcons . "list-text.png"),
    new block('Space', $defaultPath . "spacerBlock.html", $urlToIcons . "line-spacing.png"),
    new block('Empty Block', $defaultPath . "emptyBlock.html", $urlToIcons . "cubes.png"),
    new block('Columns', $defaultPath . "gridBlock.html", $urlToIcons . "column.png"),
    new block('Image', $defaultPath . "mediaBlock.html", $urlToIcons . "image.png")
];


$collection = new BlockCollection("Default Blocks", $blocks, $urlToIcons . "blocks.png");

createBlockCollection("Default Blocks", $collection);