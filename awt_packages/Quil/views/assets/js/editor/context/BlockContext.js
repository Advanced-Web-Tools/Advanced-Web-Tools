import {TextElement} from "../default/conditions/DefaultConditions";

export class BlockContext {
    constructor(block, clipboard) {
        this.currentSelector = block;
        this.clipboard = clipboard;
    }

    setSelector(block) {
        this.currentSelector = block;
    }

    moveBlockAfter() {
        if (this.currentSelector && this.currentSelector.length) {
            const $currentBlock = this.currentSelector;
            const $parent = $currentBlock.closest('.page');

            if ($parent.length && $parent.parent().hasClass('editor')) {
                const $nextBlock = $currentBlock.nextAll('.block').first();

                if ($nextBlock.length) {
                    $currentBlock.insertAfter($nextBlock);
                } else {
                    $parent.append($currentBlock);
                    console.warn("No next block found, moved block to the end.");
                }
            } else {
                console.warn("Cannot move block outside of .editor .page.");
            }
        }

        this.reattachEventsToScene();
    }

    moveBlockBefore() {
        if (this.currentSelector && this.currentSelector.length) {
            const $currentBlock = this.currentSelector;
            const $parent = $currentBlock.closest('.page');

            // Ensure the block is inside the .editor .page container
            if ($parent.length && $parent.parent().hasClass('editor')) {
                const $prevBlock = $currentBlock.prevAll('.block').first();

                if ($prevBlock.length) {
                    // Move before the previous adjacent block
                    $currentBlock.insertBefore($prevBlock);
                } else {
                    // If no previous block, move it to the start of the .page container
                    $parent.prepend($currentBlock);
                    console.warn("No previous block found, moved block to the start.");
                }
            } else {
                console.warn("Cannot move block outside of .editor .page.");
            }
        }

        this.reattachEventsToScene();
    }

    createBlockContext(blockElement) {
        const contextWrapper = document.createElement("div");
        contextWrapper.classList.add("contextWrapper");

        const blockContext = document.createElement('div');
        blockContext.classList.add('blockContext');

        const titleDiv = document.createElement('div');
        titleDiv.classList.add('title');

        const titleP = document.createElement('p');
        titleP.setAttribute("contenteditable", "false");
        titleP.classList.add('block_name');
        titleP.innerText = $(blockElement).attr("block-name"); // Placeholder name, can be dynamic
        titleDiv.appendChild(titleP);

        const spacer = document.createElement("hr");

        const deleteButton = document.createElement('button');
        deleteButton.id = 'delete';
        deleteButton.title = 'Delete block.';
        deleteButton.classList.add('btn_action_negative');
        deleteButton.innerHTML = '<i class="no_mrg fa-solid fa-trash"></i>';

        deleteButton.addEventListener('click', (e) => {
            e.stopPropagation();
            e.preventDefault();
            blockElement.remove();
            contextWrapper.remove();
            this.reattachEventsToScene();
        });

        const moveUpButton = document.createElement('button');
        moveUpButton.id = 'moveUp';
        moveUpButton.title = 'Move selected block before next block.';
        moveUpButton.classList.add('btn_primary', 'no_rad');
        moveUpButton.innerHTML = '<i class="no_mrg fa-solid fa-chevron-up"></i>';
        moveUpButton.addEventListener('click', (e) => {
            e.stopPropagation();
            e.preventDefault();
            this.moveBlockBefore(blockElement);
        });

        const moveDownButton = document.createElement('button');
        moveDownButton.id = 'moveDown';
        moveDownButton.title = 'Move selected block after next block.';
        moveDownButton.classList.add('btn_primary', 'no_rad');
        moveDownButton.innerHTML = '<i class="no_mrg fa-solid fa-chevron-down"></i>';
        moveDownButton.addEventListener('click', (e) => {
            e.stopPropagation();
            e.preventDefault();
            this.moveBlockAfter(blockElement);
        });

        const editText = document.createElement("button");
        editText.id = "editText";
        editText.title = "Edit text.";
        editText.classList.add("btn_primary", "no_rad");
        editText.innerHTML = '<i class="no_mrg fa-solid fa-pen"></i>';

        editText.addEventListener("click", (e) => {
            e.stopPropagation();
            e.preventDefault();
            if (editText.classList.contains("active")) {
                editText.classList.remove("active");
                this.removeEditingText();
            } else {
                editText.classList.add("active", 'true');
                this.editText();
            }
        });


        const parentSelector = document.createElement("button");
        parentSelector.id = "parentSelect";
        parentSelector.title = "Select parent.";
        parentSelector.classList.add("btn_primary", "no_rad");
        parentSelector.innerHTML = '<i class="no_mrg fa-solid fa-arrow-up"></i>';

        parentSelector.addEventListener("click", (e) => {
            e.stopPropagation();
            e.preventDefault();
            this.currentSelector.parent().click();
        });

        const childSelector = document.createElement("button");
        childSelector.id = "childSelect";
        childSelector.title = "Select first child.";
        childSelector.classList.add("btn_primary", "no_rad");
        childSelector.innerHTML = '<i class="no_mrg fa-solid fa-arrow-down"></i>';

        childSelector.addEventListener("click", (e) => {
            e.stopPropagation();
            e.preventDefault();
            this.currentSelector.children()[0].click();
        });


        const subMenu = document.createElement("button");
        subMenu.id = "subMenuOpen";
        subMenu.title = "Open sub-menu.";
        subMenu.classList.add("btn_primary", "no_rad");
        subMenu.innerHTML = '<i style="font-size: 1.2rem !important;" class="no_mrg fas fa-ellipsis-vertical"></i>';

        subMenu.addEventListener("click", (e) => {
            e.stopPropagation();
            e.preventDefault();
            if (e.currentTarget.classList.contains("active")) {
                e.currentTarget.classList.remove("active");
                blockContext.querySelector(".sub-context-wrapper").classList.remove("active");
            } else {
                e.currentTarget.classList.add("active");
                blockContext.querySelector(".sub-context-wrapper").classList.add("active");
            }

        });


        const actions = document.createElement("div");
        actions.classList.add("actions");

        blockContext.appendChild(titleDiv);

        if (TextElement(this.currentSelector)) {
            actions.appendChild(editText);
            actions.appendChild(spacer.cloneNode(true));
        }
        actions.appendChild(parentSelector);
        actions.appendChild(childSelector);
        actions.appendChild(spacer.cloneNode(true));
        actions.appendChild(moveUpButton);
        actions.appendChild(moveDownButton);
        actions.appendChild(spacer.cloneNode(true));
        actions.appendChild(deleteButton);
        actions.appendChild(spacer.cloneNode(true));
        actions.appendChild(subMenu);

        blockContext.appendChild(actions);
        blockContext.appendChild(this.subContext());
        contextWrapper.appendChild(blockContext);


        if (!blockElement.appendChild(contextWrapper)) {
            const parentElement = blockElement.parentElement;
            if (parentElement && parentElement.classList.contains('block')) {
                parentElement.appendChild(contextWrapper);
            }
        }
    }

    subContext() {

        const spacer = document.createElement("hr");

        const subContext = document.createElement("div");
        subContext.classList.add("sub-context-wrapper");


        const subContextActions = document.createElement("ul");
        subContextActions.classList.add("sub-context-actions");


        const subActionCopy = document.createElement("li");
        subActionCopy.id = "subActionCopy";
        subActionCopy.title = "Copy element";
        subActionCopy.innerHTML = '<button class="btn_primary no_rad">Copy <i class="fa-solid fa-copy"></i></button>';

        subActionCopy.addEventListener("click", (e) => {
            e.stopPropagation();
            e.preventDefault();

            this.copyBlock();

        });

        subContextActions.appendChild(subActionCopy);

        const subActionCut = document.createElement("li");
        subActionCut.id = "subActionCut";
        subActionCut.title = "Cut element.";
        subActionCut.innerHTML = '<button class="btn_primary no_rad">Cut <i class="fa-solid fa-scissors"></i></button>';

        subActionCut.addEventListener("click", (e) => {
            e.stopPropagation();
            e.preventDefault();

            this.cutBlock();
        });

        subContextActions.appendChild(subActionCut);
        subContextActions.appendChild(spacer.cloneNode(true));

        const subActionPaste = document.createElement("li");
        subActionPaste.id = "subActionPaste";
        subActionPaste.title = "Paste whole block from clipboard.";
        subActionPaste.innerHTML = '<button class="btn_primary no_rad">Paste block <i class="fa-solid fa-paste"></i></button>';

        subActionPaste.addEventListener("click", (e) => {
            e.stopPropagation();
            e.preventDefault();

            this.pasteBlock();
        });

        subContextActions.appendChild(subActionPaste);

        const subActionPasteContent = document.createElement("li");
        subActionPasteContent.id = "subActionPaste";
        subActionPasteContent.title = "Paste block content from clipboard.";
        subActionPasteContent.innerHTML = '<button class="btn_primary no_rad">Paste content <i class="fa-solid fa-paste"></i></button>';

        subActionPasteContent.addEventListener("click", (e) => {
            e.stopPropagation();
            e.preventDefault();

            this.pasteBlockContent();
        });

        subContextActions.appendChild(subActionPasteContent);

        const subActionPasteStyle = document.createElement("li");
        subActionPasteStyle.id = "subActionPaste";
        subActionPasteStyle.title = "Replace block style with the copied blocks style.";
        subActionPasteStyle.innerHTML = '<button class="btn_primary no_rad">Replace style <i class="fa-solid fa-vest"></i></button>';

        subActionPasteStyle.addEventListener("click", (e) => {
            e.stopPropagation();
            e.preventDefault();

            this.pasteBlockCSS();
        });

        subContextActions.appendChild(subActionPasteStyle);

        const subActionReplaceBlock = document.createElement("li");
        subActionReplaceBlock.id = "subActionPaste";
        subActionReplaceBlock.title = "Replace block with the copied block.";
        subActionReplaceBlock.innerHTML = '<button class="btn_primary no_rad">Replace block <i class="fa-solid fa-repeat"></i></button>';

        subActionReplaceBlock.addEventListener("click", (e) => {
            e.stopPropagation();
            e.preventDefault();

            this.replaceBlock();
        });

        subContextActions.appendChild(subActionReplaceBlock);

        subContext.appendChild(subContextActions);
        return subContext;
    }

    copyBlock() {
        const content = this.currentSelector.clone();

        content.find(".insert").remove();
        content.find(".context-wrapper").remove();
        content.find(".blockContext").remove();

        this.clipboard.setContent(content);
    }

    cutBlock() {
        const content = this.currentSelector.clone();

        content.find(".insert").remove();
        content.find(".context-wrapper").remove();
        content.find(".blockContext").remove();

        this.clipboard.setContent(content);

        this.currentSelector.remove();
        this.reattachEventsToScene();
    }

    pasteBlock() {
        this.currentSelector.append(this.clipboard.getContent().clone());
        this.reattachEventsToScene();
    }

    pasteBlockContent() {
        const existingContent = this.currentSelector.html();
        const newContent = this.clipboard.getContent().clone()[0].innerHTML;
        this.currentSelector.html(existingContent + newContent);
        this.reattachEventsToScene();
    }

    replaceBlock() {
        const content = this.clipboard.getContent()?.clone();

        if (content && content.length) {
            this.currentSelector.replaceWith(content);
            this.reattachEventsToScene();
        } else {
            console.error("Clipboard content is empty or invalid.");
        }
    }

    pasteBlockCSS() {
        const clipboardContent = this.clipboard.getContent().clone();
        if (clipboardContent && clipboardContent.length) {
            const newCSS = clipboardContent.clone().attr("style");

            this.currentSelector.attr("style", newCSS);

            this.reattachEventsToScene();
        } else {
            console.error("Clipboard is empty or invalid.");
        }
    }

    editText() {
        this.currentSelector.attr("contenteditable", "true");
        this.currentSelector[0].focus();
    }

}