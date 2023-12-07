var $blocksAll = $(".block");
var $lastElement;
$(document).ready(function() {
    $blocksAll = $(".pageSection .block");
    insertBlock();
});

$

function insertBlock() {
    let $currentElement = null;
    let timeoutId = null;
    let $newBlock = null;

    $(document).on('mouseenter', '.block', handleMouseEnter);
    
    function handleMouseEnter(event) {
        $currentElement = $(event.currentTarget);
        if ($currentElement.hasClass("replacable")) return;
        if ($currentElement.attr('id') == "grid-block") return;
        if ($currentElement.parent().attr('id') == "grid-block") return;
        if (floatingBlockSelectorActive) return;

        if (movingBlocks) {
            removeNewBlock();
            return;
        }

        removeNewBlock();

        if (timeoutId) {
            clearTimeout(timeoutId);
        }

        if ($currentElement.next('.block')) {
            timeoutId = setTimeout(function () {
                if ($currentElement.hasClass("replacable")) return;
                removeNewBlock();

                $newBlock = $('<div class="block replacable"></div>');
                $currentElement.after($newBlock);

                $newBlock.on("click", function () {
                    floatingBlockSelectorActive = true;
                    floatingBlocks($newBlock);
                });

            }, 700);
        }
    }

    function removeNewBlock() {
        if ($newBlock) {
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
        $(document).off('mouseenter', '.block');
        insertBlock();
    });
}
