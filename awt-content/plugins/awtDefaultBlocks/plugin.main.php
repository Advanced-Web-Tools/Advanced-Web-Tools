<?php

use blocks\{block, BlockCollection, BlockOptions};

$collection = "awtDefaultBlocks";
$defaultPath = PLUGINS.$collection.DIRECTORY_SEPARATOR."src".DIRECTORY_SEPARATOR;

$urlToIcons = HOSTNAME . "/awt-content/plugins/" . $collection . "/data/icons/";

$blocks = [
    new block('Title', $defaultPath . "titleBlock.html", $urlToIcons . "title.png", "Adds a large H1 text to your page."),
    new block('Ordered List', $defaultPath . "listBlock.html", $urlToIcons . "list-text.png", "Creates a numbered list. You can add/remove items in options panel."),
    new block('Unordered List', $defaultPath . "unorderedList.html", $urlToIcons . "list-text.png", "Creates a bullet list. You can add/remove items in options panel."),
    new block('Space', $defaultPath . "spacerBlock.html", $urlToIcons . "line-spacing.png", "Add space between elements. You can set height or width to space elements even more."),
    new block('Empty Block', $defaultPath . "emptyBlock.html", $urlToIcons . "cubes.png", "An empty block that can be used to group or stack other blocks together."),
    new block('Columns', $defaultPath . "gridBlock.html", $urlToIcons . "column.png", "Adds horizontal columns to your page. Column direction can be changed in options panel."),
    new block('Image', $defaultPath . "mediaBlock.html", $urlToIcons . "image.png", "Import an image from your media library. Image can be changed from options panel.")
];


$collection = new BlockCollection("Default Blocks", $blocks, $urlToIcons . "blocks.png");

createBlockCollection("Default Blocks", $collection);