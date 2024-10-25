import {Editor} from "./Editor";
import {Clipboard} from "./clipboard/Clipboard";
import {BlockContext} from "./context/BlockContext";

export class Scene {
    constructor(editorPage, blocks, options) {
        this.editor = editorPage;
        this.currentSelector = editorPage;
        this.blocks = blocks;
        this.options = options;

        this.insert = this.createInsertElement();
        this.insertLocked = $("<hr class='insert' save='false' locked='true' replaceable='true'></hr>");

        this.clipboard = new Clipboard();

        this.blockContext = new BlockContext(this.currentSelector, this.clipboard);

        this.editor.append(this.insert);
        this.reattachEventsToScene();
    }

    setSelector(select) {
        this.currentSelector = $(select);

        $("[selected='selected']").removeAttr("selected")

        this.currentSelector.attr("selected", "true")
    }

    selectBlock(e) {

        if (e.currentTarget !== this.currentSelector[0]) {
            this.removeEditingText();
        }

        this.setSelector(e.currentTarget);

        if (!this.currentSelector.hasClass("insert")) {
            this.options.setCurrentBlock(this.currentSelector);

            this.options.startRender();

            if (!this.currentSelector.is('.editor .page')) {
                this.insert.remove();
                this.insert = this.createInsertElement();
                this.insert.insertAfter(this.currentSelector);
            }
        } else {
            if (this.currentSelector.attr("locked") !== "true") {
                this.insert.remove();
                this.insert = this.insertLocked.clone();
                this.insert.insertAfter(this.currentSelector);
                this.currentSelector.attr("locked", "true");
                if ($(".main .left").hasClass("hidden")) {
                    new Editor().openBlockSelector($(".main .left"));
                }
            }
        }
        this.reattachEventsToScene();
    }

    createInsertElement() {
        return $("<hr class='insert' save='false' locked='false' replaceable='true'></hr>");
    }

    moveInserter(block) {
        const target = $(block);

        if (target.hasClass("insert") || target.is('.editor .page') || this.insert.attr("locked") === "true") {
            return;
        }

        if (this.insert.is(target)) {
            return; // No need to move if the inserter is already below the target
        }

        const newInsert = this.createInsertElement();
        newInsert.insertAfter(target);
        this.insert.remove();
        this.insert = newInsert;

        this.reattachEventsToScene();
    }

    removeEditingText() {
        this.editor.find('[contenteditable="true"]').removeAttr("contenteditable")
    }

    addBlock(e) {
        const clickedBlock = $(e.currentTarget);
        const name = clickedBlock.attr("name");
        const category = clickedBlock.attr("category");

        const newContent = $(this.blocks.getBlock(category, name).getBody());

        const randomClass = `${Math.floor(Math.random() * 10000)}`;
        newContent.addClass(randomClass);

        if (this.currentSelector.hasClass("block") || this.currentSelector.hasClass("insert")) {
            if (this.currentSelector.attr("replaceable") === "true") {
                this.currentSelector.replaceWith(newContent);
            } else {
                this.currentSelector.append(newContent);
            }
        } else {
            this.editor.append(newContent);
        }

        this.reattachEventsToScene();
        newContent.click();
        this.markEmptyBlocks();
    }

    markEmptyBlocks() {
        this.editor.find(".block").each(function (index, block) {
            const children = $(block).children();

            if (children.length === 1 && children.hasClass('contextWrapper') && $(block).is("div")) {
                $(block).addClass("empty");
            } else if (children.length === 0 && $(block).is("div")) {
                $(block).addClass("empty");
            } else {
                $(block).removeClass("empty");
            }
        });
    }

    reattachEventsToScene() {
        const blockSelector = $(".editor .page .block");

        blockSelector.off("mouseenter").on("mouseenter", (e) => {
            e.stopPropagation();
            this.moveInserter(e.currentTarget);
        });

        blockSelector.off("click").on("click", (e) => {
            e.stopPropagation();
            e.preventDefault();
            $(".contextWrapper").remove();
            this.selectBlock(e);
            this.blockContext.setSelector(this.currentSelector);
            this.blockContext.createBlockContext(e.currentTarget);
            document.querySelector('.color-input').dispatchEvent(new Event('input', {bubbles: true}));
        });

        this.editor.off("click").on("click", (e) => {
            e.stopPropagation();
            this.selectBlock(e);
            $(".contextWrapper").remove();
        });

        this.editor.find(".insert").off("click").on("click", (e) => {
            e.stopPropagation();

            this.insert.replaceWith(this.insertLocked);
            this.insert = this.insertLocked;

            this.setSelector(this.insert);
            if ($(".main .left").hasClass("hidden")) {
                new Editor().openBlockSelector($(".main .left"));
            }

            this.reattachEventsToScene();
            console.log("insert click");
        });

        $(".main .left .content .child.block").off("click").on("click", (e) => {
            this.addBlock(e);
        });
    }
}
