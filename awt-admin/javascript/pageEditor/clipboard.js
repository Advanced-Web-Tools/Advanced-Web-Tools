var $clipBoard = null;

var currentMousePos = { x: -1, y: -1 };

$(document).mousemove(function (event) {
    currentMousePos.x = event.pageX;
    currentMousePos.y = event.pageY;
});


function paste() {

    console.log("paste")

    if(!$clipBoard) return;
    $clipBoard.removeClass('selected');

    if($('.selected').length > 0) {
      $clipBoard.click(function (event) {
        event.stopPropagation();
        BlockOptions(this);
      });

      $('.selected').append($clipBoard);

      saveToHistory();

      $clipBoard = $clipBoard.clone();

      $(document).off('mouseenter', '.block');

      insertBlock();

      $("* .replacable").remove();

      return;
    }
    
  
    var $contextMenu = $('.context-menu');
    var contextOffset = $contextMenu.offset();
    var nearestBlock = null;
    var minDistance = Number.MAX_VALUE;
  
    var $blocks = $('.block');
  
    $blocks.each(function () {
      var $block = $(this);
      var blockOffset = $block.offset();
      var blockCenterX = blockOffset.left;
      var blockCenterY = blockOffset.top;
  
      var distance = Math.sqrt(
        (contextOffset.left  - blockCenterX) ** 2 + (contextOffset.top - blockCenterY) ** 2
      );
  
      if (distance < minDistance) {
        minDistance = distance;
        nearestBlock = $block;
      }
    });
  
    if (nearestBlock !== null) {
      $clipBoard.removeClass('selected');
      $clipBoard.click(function (event) {
        event.stopPropagation();
        BlockOptions(this);
      });
      nearestBlock.after($clipBoard);
      saveToHistory();
      $clipBoard = $clipBoard.clone();
      $(document).off('mouseenter', '.block');
      $("* .replacable").remove();
      insertBlock();  
    }
  }
  
  
  function cut() {
    $clipBoard = $(".block.selected").clone();
    $(".block.selected").remove();
    saveToHistory();
  }
  
  function copy() {
    $clipBoard = $(".block.selected").clone();
    $('*').removeClass("selected");
  }