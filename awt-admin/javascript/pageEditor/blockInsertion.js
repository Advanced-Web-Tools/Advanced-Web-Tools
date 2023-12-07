var $blocksAll = $(".block");

$(document).ready(function() {
    $blocksAll = $(".pageSection .block");
    insertBlock();
});

function insertBlock() {
    let $currentElement = null;
    let timeoutId = null;
    var $newBlock = null;

    $(document).on('mouseenter', '.block', handleMouseEnter);

    function handleMouseEnter(event) {
        $currentElement = $(event.currentTarget);

        if (floatingBlockSelectorActive) return;
        if ($currentElement.hasClass("replacable")) return;

        if (movingBlocks) {
            removeNewBlock();
            return;
        }

        if (timeoutId) {
            clearTimeout(timeoutId);
        }

        if ($currentElement.next('.block')) {
            timeoutId = setTimeout(function () {
                removeNewBlock();

                $newBlock = $('<div class="block replacable"></div>');
                $currentElement.after($newBlock);

                $newBlock.on("click", function () {
                    floatingBlockSelectorActive = true;
                    floatingBlocks($newBlock);
                });

                // Rebind event handler for the new block
                $newBlock.on("mouseenter", function () {
                    if (floatingBlockSelectorActive) return;
                    removeNewBlock();
                });
            }, 700);
        }
    }

    function removeNewBlock() {
        if ($newBlock) {
            if ($currentElement.next('.block').hasClass("replacable")) return;
            if ($currentElement.hasClass("replacable")) return;
            $newBlock.remove();
            $newBlock = null;
            clearTimeout(timeoutId);
        }
    }
}

function floatingBlocks(caller) {
    $('.floating-blocks').removeClass('hidden');
    $('.floating-blocks').draggable();
    fetchBlocks('.floating-blocks .block-container', caller, function () {
        $(".floating-blocks").addClass('hidden');
        $(document).off('mouseenter', '.block', handleMouseEnter);
        insertBlock();  // Rebind the event handler for the new blocks
    });
}
