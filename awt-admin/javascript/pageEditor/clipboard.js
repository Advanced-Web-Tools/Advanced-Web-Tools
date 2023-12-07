var $clipBoard = null;

var currentMousePos = { x: -1, y: -1 };

$(document).mousemove(function (event) {
    currentMousePos.x = event.pageX;
    currentMousePos.y = event.pageY;
});


function paste() {

    if($('.selected') && !$('.selected').hasClass('.pageSection')) {
      $clipBoard.removeClass('selected');
      $clipBoard.click(function (event) {
        event.stopPropagation();
        BlockOptions(this);
      });
      $('.selected').append($clipBoard);
      saveToHistory();
      $clipBoard = $clipBoard.clone();
      return;
    }
    
  
    var $contextMenu = $('.context-menu');
    var contextOffset = $contextMenu.offset();
    var nearestBlock = null;
    var minDistance = Number.MAX_VALUE;
  
    var $blocks = $('.block').not($contextMenu);
  
    $blocks.each(function () {
      var $block = $(this);
      var blockOffset = $block.offset();
      var blockCenterX = blockOffset.left + $block.width() / 2;
      var blockCenterY = blockOffset.top + $block.height() / 2;
  
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
      $clipBoard.insertAfter(nearestBlock);
      saveToHistory();
      $clipBoard = $clipBoard.clone();
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