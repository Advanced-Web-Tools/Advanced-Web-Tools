export class Blocks {
    constructor() {
        this.blocks = {}; // Change to an object for categories
    }

    // Add a block to the appropriate category and name
    addBlock(block) {
        const category = block.getCategory();

        // Ensure the category exists as an object
        if (!this.blocks[category]) {
            this.blocks[category] = {}; // Initialize category if it doesn't exist
        }

        // Add the block under the specific category and name
        this.blocks[category][block.getName()] = block;
    }

    getBlock(category, name) {
        // Ensure both category and name exist before returning the block
        return this.blocks[category] && this.blocks[category][name]
            ? this.blocks[category][name]
            : null;
    }

    drawList(container) {
        let parent = $(container);
        for (const category in this.blocks) {
            if (this.blocks.hasOwnProperty(category)) {
                const wrapperDiv = $("<div></div>"); // Main wrapper div
                wrapperDiv.addClass("block_group");
                const title = $("<h5 class='cat_name'></h5>").text(category); // Category name in h5
                const title_icon = $('<i class="fa-solid fa-chevron-right"></i>');
                title.append(title_icon );
                // Click event to toggle visibility of child blocks
                title.click((e) => {
                    $(container + " .blocks[category='" + category + "']").toggleClass("hidden");
                    $(e.currentTarget).find("i").toggleClass("active");
                });

                const blocksContainer = $("<div class='blocks hidden'></div>"); // Container for blocks
                blocksContainer.attr("category", category); // Add category attribute

                // Iterate over the blocks within the current category
                for (const blockName in this.blocks[category]) {
                    if (this.blocks[category].hasOwnProperty(blockName)) {
                        const block = this.blocks[category][blockName];

                        // Create the block element
                        const child = $("<div></div>");
                        child.addClass(["child", "block"]); // Add classes 'block' and 'child'
                        child.attr("category", block.category);
                        child.attr("name", block.name);

                        // Create and append block name
                        const fa_icon = $("<i></i>");
                        fa_icon.addClass(block.fa_icon_class);
                        const childName = $("<p></p>");
                        childName.text(block.getName());

                        child.append(fa_icon);
                        child.append(childName);

                        // Append the child block to blocks container
                        blocksContainer.append(child);
                    }
                }

                // Append title and blocks container to the wrapper div
                wrapperDiv.append(title);
                wrapperDiv.append(blocksContainer);

                // Append the final wrapper div to the parent container
                parent.append(wrapperDiv);
            }
        }

    }

}
