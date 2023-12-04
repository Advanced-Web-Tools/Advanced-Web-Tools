
var $selection = $(".pageSection");

var shrinkenView = false;

var movingBlocks = false;

var selectedElement = false;

var blockPositions = []; // Array to store the positions of blocks

var ctrlPressed = false;

var ignoreSave = true;

var updatingFromHistory = false;

var pageHistory = [];
var currentIndex = -1;

var floatingBlockSelectorActive = false;

var ignorePreviewClick = false;


function fetchBlocks(element, replacable = null) {

  $(element).find('.category-container').remove();

  $.ajax({
    url: './jobs/pageEditor.php',
    type: 'POST',
    data: {
      getBlocks: 1
    },
    success: function (response) {
      try {
        var parsedResponse = JSON.parse(response);

        if (parsedResponse && typeof parsedResponse === 'object') {
          for (var category in parsedResponse) {
            if (Array.isArray(parsedResponse[category])) {
              // Create a label for the category
              var categoryLabel = $('<h4>').text(category);
              categoryLabel.addClass(category.replace(/ /g, '-'));

              // Create a container div for the category and its child elements
              var categoryContainer = $('<div>').addClass('category-container');
              // Append the category label to the container
              categoryContainer.append(categoryLabel);
              categoryLabel.append('<i class="fa-solid fa-layer-group" style="margin-left: 20px;"></i>');


              // Iterate through the blocks in the category
              parsedResponse[category].forEach(function (block) {
                // Create a new child element
                var childElement = $('<div>').addClass('block-item hidden ' + category.replace(/ /g, '-'));

                // Create a <p> tag for each item in the parsed response array
                var itemElement = $('<p>').text(block.name);

                // Append the item element to the child element
                childElement.append(itemElement);

                // Attach onclick event to the child element
                childElement.click(function () {
                  getBlock(block.name, replacable);
                });

                // Append the child element to the category container
                categoryContainer.append(childElement);
              });

              // Append the category container to the specified parent element
              $(element).append(categoryContainer);

              // Add a click event to toggle the visibility of child elements
              categoryLabel.click(function (catClass) {
                return function () {
                  $('.category-container .block-item.' + catClass).toggleClass('hidden');
                }
              }(category.replace(" ", "-")));
            }
          }

          // Make the blocks stackable inside the preview element
          $('.pageSection').children().each(function () {
            if ($(this).is('div') && $(this).hasClass('block')) {
              $(this).addClass('stackable');
            }
          });
        } else {
          console.log('Parsed response is not in the expected format.');
        }
      } catch (error) {
        console.log('Error parsing the response as JSON.');
      }
    },
    error: function (xhr, status, error) {
      console.log('AJAX request failed.');
      console.log(error);
    }
  });
}



function getBlock(name, replacable = null) {
  $.ajax({
    url: './jobs/pageEditor.php',
    type: 'POST',
    data: {
      getBlock: name
    },
    success: function (response) {

      if(replacable !== null) {
        $(replacable).replaceWith(response);
      } else {
        $selection.append(response);
      }


      hasTextChild($(".block")).attr("contenteditable", "true");
      
      // Attach BlockOptions function to click event of block and its direct children
      $(".block").on("click", function () {
        BlockOptions($(this));
      }).children().on("click", function (e) {
        e.stopPropagation(); // Prevent event bubbling to the parent block
      });
    },
    error: function (xhr, status, error) {
      console.log('AJAX request failed.');
      console.log(error);
    }
  });
  
}


function rgbToHex(rgbColor) {

  if (rgbColor === null) return;
  // Extract the RGB components from the color string
  var rgbValues = rgbColor.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
  if (!rgbValues) {
    return null; // Invalid RGB color string
  }

  // Convert each RGB component to hexadecimal
  var hexValues = rgbValues.slice(1).map(function (value) {
    var hex = parseInt(value, 10).toString(16);
    return hex.length === 1 ? "0" + hex : hex;
  });

  // Combine the hexadecimal values
  var hexColor = "#" + hexValues.join("");

  return hexColor;
}


function BlockOptions(element) {
  var $block = $(element);
  $('*').removeClass("selected");
  $block.addClass("selected");
  $selection = $block;
  var defaultStyle = $block.attr("style");

  setDefaultOptions($block, defaultStyle);

  if (hasTextChild($block).length > 0) {
    setTextOptions($block, defaultStyle);
  }

  if (hasListChild($block).length > 0) {
    ListOptions($block, defaultStyle);
  }

  if (isMedia($block)) {
    mediaOptions($block, defaultStyle);
  }

  if ($block.attr("id") === "grid-block") {
    setGridOptions($block, defaultStyle);
  }

  $(".add-child").off("click").on("click", function () {
    var newChildElement = '<div class="block"></div>';
    $block.append(newChildElement);
    $block.children().last().click(function (event) {
      event.stopPropagation();
      BlockOptions(this);
    });
  });

  $(".create-child-button").off("click").on("click", function () {
    var newChildElement = '<div class="block"></div>';
    $block.append(newChildElement);
    $block.children().last().click(function (event) {
      event.stopPropagation();
      BlockOptions(this);
    });
  });
}

function updateBlockPositions() {
  blockPositions = [];
  $(".pageSection .block").each(function () {
    blockPositions.push($(this).attr("id"));
  });
}

function publishContent(name) {
  var $pageSection = $('.pageSection');
  
  // Clone the page section to avoid modifying the actual content
  var $clonedPageSection = $pageSection.clone();

  // Remove the specified elements from the cloned page section
  $clonedPageSection.find(".block.empty.replacable").remove();
  $pageSection.find(".block.empty.replacable").remove();

  // Get the HTML content of the modified page section
  var htmlContent = $clonedPageSection.last().prop('outerHTML');

  $.ajax({
    url: './jobs/pageEditor.php',
    type: 'POST',
    data: {
      htmlContent: htmlContent,
      name: name,
      pageStatus: "live"
    },
    success: function (response) {
      // Handle the success response if needed
    },
    error: function (xhr, status, error) {
      console.log(error);
    }
  });
}


function publishContentPreview(name) {
  var $pageSection = $('.pageSection');
  
  // Clone the page section to avoid modifying the actual content
  var $clonedPageSection = $pageSection.clone();

  // Remove the specified elements from the cloned page section
  $clonedPageSection.find(".block.empty.replacable").remove();

  // Get the HTML content of the modified page section
  var htmlContent = $clonedPageSection.last().prop('outerHTML');

  $.ajax({
    url: './jobs/pageEditor.php',
    type: 'POST',
    data: {
      htmlContent: htmlContent,
      name: name,
      pageStatus: "preview"
    },
    success: function (response) {
    },
    error: function (xhr, status, error) {
      console.log(error);
    }
  });
}


function changeViewPort(caller) {
  var $preview = $('.preview');

  if (!shrinkenView) {
    $preview.css({
      "width": '375px',
      "margin": 'auto auto',
      "height": '667px',
      "border": '2px solid #000',
      "overflow": 'auto' // Add overflow control
    });
    shrinkenView = true;
    $(caller).addClass("active");
  } else {
    $preview.css({
      "width": 'auto',
      "height": '100%',
      "border": 'none',
      "overflow": 'auto' // Reset overflow
    });
    shrinkenView = false;
    $(caller).removeClass("active");
  }
}

function detectEmpty() {
  $('.preview').find('.block').each(function (index, block) {
    if ($(block).children().length == 0 && $(block).text().trim().length == 0 && $(block).is("div")) {
      if($(block).hasClass("empty") == false) $(block).addClass("empty");
    } else {
      $(block).removeClass("empty");
    }
  });
}


function trackMouseBetweenBlocks() {
  var $blocks = $('.pageSection .block');
  var $newBlock = null;
  var $currentElement = null;

  $blocks.on('mouseenter', function() {
    $currentElement = $(this);
    if($currentElement.attr("id") === "grid-block") return;

    if($currentElement.parent().attr("id") === "grid-block") return;

    if(floatingBlockSelectorActive) return;
    
    ignoreSave = true;
    if(movingBlocks) {
      $newBlock.remove();
      return;
    }
    // Check if there is a previous or next sibling
    if ($currentElement.prev('.block').length && $currentElement.next('.block').length) {
      
      // Remove the old new block if it exists
      if ($newBlock) {
        $newBlock.remove();
      }


      // Create a new element
      $newBlock = $('<div class="block replacable"></div>');

      // Insert the new element after the current element
      $currentElement.after($newBlock);

      $newBlock.on("click", function(){
        floatingBlockSelectorActive = true;
        floatingBlocks($newBlock);
      });

    }
  });

  $blocks.on('mouseleave', function() {
    if ($currentElement && $currentElement.next('.block').length && $currentElement.next('.block').hasClass('new-block')) {
      $currentElement = null;
    }

    // Remove the new block if the mouse exits the current element
  });

  $('.pageSection').on('mouseenter', '.new-block', function() {
    // Prevent removing the new block when entering between two new blocks
    if ($currentElement) {
      $currentElement = null;
    }
  });

  $('.preview').on('mouseleave', '.new-block', function() {
    // Remove the new block when leaving the space between two new blocks
    if ($newBlock) {
      $newBlock.remove();
    }
  })

  $(".preview").on("DOMSubtreeModified", function (event) {
    if(movingBlocks) $newBlock.remove();
  });

}


function updateFromHistory() {
  updatingFromHistory = true;
  if(currentIndex !== 0) $('.pageSection').html(pageHistory[currentIndex - 1]);
  createEditableLayout();
}

function saveToHistory() {
  var content = $(".pageSection").html();
  pageHistory.push(content);
  currentIndex++;
}

function createEditableLayout() {
  $selection = $('.pageSection');

  $(".pageSection").sortable({
    items: ".block",
    scroll: true,
    axis: 'y',
    containment: "parent",
    tolerance: "pointer",
    cancel: 'input,textarea,button,select,option,[contenteditable]',
    start: function(event, ui) {
      movingBlocks = true;
    },
    update: function (event, ui) {
      // Update the block positions array
      updateBlockPositions();
    },
    stop: function(event, ui) {
      movingBlocks = false;
    }
  });

  $('.pageSection .block').each(function () {
    var $block = $(this);

    $block.on('click', function () {
      BlockOptions($block);
    });

    hasTextChild($block).attr('contenteditable', 'true');

    $block.find('.block').on('click', function (e) {
      e.stopPropagation();
      BlockOptions($block);
    });
  });

  $(".preview").click(function () {
    if (ignorePreviewClick) {
      ignorePreviewClick = false; // Reset the flag
      return; // Ignore the click event
    }
    $selection = $('.pageSection');
    $("*").removeClass('selected');
    $(".pageSection").addClass('selected');
    if($selection.find(".selected").length === 0) selectedElement = true;
  });

  $(".pageSection").on("click", function (event) {
    // Check if the clicked element is a child of .pageSection
    if (
      $(event.target).closest(".pageSection").length > 0 &&
      !$(event.target).is(".preview")
    ) {
      ignorePreviewClick = true; // Set the flag to ignore the next preview click event
      return; // Ignore the click event for the parent .pageSection element
    }

    // Process the click event on .pageSection here
    BlockOptions(this);
    fetchBlocks('.editor-tools');

    
  });
}


function floatingBlocks(caller) {
  $('.floating-blocks').removeClass('hidden');
  fetchBlocks('.floating-blocks .block-container', caller);
  $('.floating-blocks').draggable();
}

$(document).ready(function () {
  
  createEditableLayout();
  
  $(".floating-blocks").on("DOMSubtreeModified", function (event) {
    if($('.floating-blocks').hasClass('hidden')) {
      floatingBlockSelectorActive = false;
    } else {
      floatingBlockSelectorActive = true;
    }
    console.log("change");
  });

  $(".preview").on("DOMSubtreeModified", function (event) {
    detectEmpty();
  });
  
  $(document).bind("keydown", function(event){
    if(event.key === "Delete" || event.keyCode === 46) $(".selected").remove();
    if(event.ctrlKey) ctrlPressed = true;
  });

  $(document).bind("keyup", function(event){
    // if(ctrlPressed && event.key === "z") {
    //   updateFromHistory();
    // } 
    if(event.ctrlKey) ctrlPressed = false;
  });

  saveToHistory();

  fetchBlocks('.editor-tools');
  trackMouseBetweenBlocks();

  $(".preview").on("DOMSubtreeModified", function (event) {
  //   if(!updatingFromHistory && !ignoreSave) saveToHistory();
  //   updatingFromHistory = false;
  //   ignoreSave = false;
  });
});


