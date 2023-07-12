function fetchBlocks(element) {
    $.ajax({
        url: './jobs/pageEditor.php',
        type: 'POST',
        data: {
            getBlocks: 1
        },
        success: function (response) {
            console.log('AJAX request succeeded.');
            console.log(response);

            try {
                var parsedResponse = JSON.parse(response);
                if (Array.isArray(parsedResponse)) {
                    parsedResponse.forEach(function (block) {
                        // Create a new child element
                        var childElement = $('<div>');

                        // Create a <p> or <h5> tag for each item in the parsed response array
                        var itemElement = $('<p>').text(block); // or $('<h5>').text(block);

                        // Append the item element to the child element
                        childElement.append(itemElement);

                        // Attach onclick event to the child element
                        childElement.click(function () {
                            getBlock(block);
                        });

                        // Append the child element to the specified parent element
                        $(element).append(childElement);
                    });

                    // Make the blocks stackable inside the preview element
                    $('.preview').children().each(function () {
                        if ($(this).is('div') && $(this).hasClass('block')) {
                            $(this).addClass('stackable');
                        }
                    });
                } else {
                    console.log('Parsed response is not an array.');
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

var blockPositions = []; // Array to store the positions of blocks

function getBlock(name) {
    $.ajax({
      url: './jobs/pageEditor.php',
      type: 'POST',
      data: {
        getBlock: name
      },
      success: function(response) {
        console.log('AJAX request succeeded.');
        $(".preview").append(response);
  
        // Make all elements with class "block" sortable within .preview
        $(".preview").sortable({
          items: ".block",
          cancel: 'input,textarea,button,select,option,[contenteditable]',
          update: function(event, ui) {
            // Update the block positions array
            updateBlockPositions();
          }
        });
  
        // Make each text element within .block editable
        $(".block").find(":not(:has(*))").attr("contenteditable", "true");
  
        // Attach BlockOptions function to click event of block and its children
        $(".block, .block *").on("click", function() {
          BlockOptions($(this).closest(".block"));
        });
  
        // Check if the parent element has the ID "grid-block"
        if ($(".block#grid-block").length > 0) {
          // Make the grid elements sortable within the grid block
          $(".block#grid-block").sortable({
            items: "> .block",
            handle: ".handle",
            update: function(event, ui) {
              // Update the grid positions array
              updateGridPositions();
            }
          });
        }
      },
      error: function(xhr, status, error) {
        console.log('AJAX request failed.');
        console.log(error);
      }
    });
  }
  

function BlockOptions(element) {
    var $block = $(element); // Convert the DOM element to a jQuery object
    
    // Get the default values from the style tag
    var defaultStyle = $block.attr("style");
    var defaultFontSize = defaultStyle ? defaultStyle.match(/font-size:\s*(\S+);/) : null;
    var defaultBackgroundColor = defaultStyle ? defaultStyle.match(/background-color:\s*(\S+);/) : null;
    var defaultWidth = defaultStyle ? defaultStyle.match(/width:\s*(\S+);/) : null;
    var defaultHeight = defaultStyle ? defaultStyle.match(/height:\s*(\S+);/) : null;
    var defaultMargin = defaultStyle ? defaultStyle.match(/margin:\s*(\S+);/) : null;
    var defaultPadding = defaultStyle ? defaultStyle.match(/padding:\s*(\S+);/) : null;
    var defaultTextColor = defaultStyle ? defaultStyle.match(/color:\s*(\S+);/) : null;
    var defaultGridTemplateColumns = defaultStyle ? defaultStyle.match(/grid-template-columns:\s*(\S+);/) : null;
    var defaultGridTemplateRows = defaultStyle ? defaultStyle.match(/grid-template-rows:\s*(\S+);/) : null;
    var defaultGridFlow = defaultStyle ? defaultStyle.match(/grid-auto-flow:\s*(\S+);/) : null;
    var defaultJustifyContent = defaultStyle ? defaultStyle.match(/justify-content:\s*(\S+);/) : null;
    var defaultAlignItems = defaultStyle ? defaultStyle.match(/align-items:\s*(\S+);/) : null;
    var defaultPlaceContent = defaultStyle ? defaultStyle.match(/place-content:\s*(\S+);/) : null;
  
    // Create the HTML for the options with default values as placeholders
    var options = '<button class="align-left">Left</button>';
    options += '<button class="align-center">Center</button>';
    options += '<button class="align-right">Right</button>';
    options += '<input type="number" class="font-size-input" value="' + (defaultFontSize ? defaultFontSize[1] : '') + '" placeholder="Font Size">';
    options += '<input type="color" class="background-color-input" value="' + (defaultBackgroundColor ? defaultBackgroundColor[1] : '') + '" placeholder="Background Color">';
    options += '<input type="text" class="width-input" value="' + (defaultWidth ? defaultWidth[1] : '') + '" placeholder="Width">';
    options += '<input type="text" class="height-input" value="' + (defaultHeight ? defaultHeight[1] : '') + '" placeholder="Height">';
    options += '<input type="text" class="margin-input" value="' + (defaultMargin ? defaultMargin[1] : '') + '" placeholder="Margin">';
    options += '<input type="text" class="padding-input" value="' + (defaultPadding ? defaultPadding[1] : '') + '" placeholder="Padding">';
    options += '<input type="color" class="text-color-input" value="' + (defaultTextColor ? defaultTextColor[1] : '') + '" placeholder="Text Color">';
  
    // Append the options to the block-options element
    $(".block-options").html(options);
  
    // Bind change event handlers to update style on input value change
    $(".font-size-input").on("input", function() {
      $block.css("font-size", $(this).val() + "px");
    });
  
    $(".background-color-input").on("input", function() {
      $block.css("background-color", $(this).val());
    });
  
    $(".width-input").on("input", function() {
      $block.css("width", $(this).val());
    });
  
    $(".height-input").on("input", function() {
      $block.css("height", $(this).val());
    });
  
    $(".margin-input").on("input", function() {
      $block.css("margin", $(this).val());
    });
  
    $(".padding-input").on("input", function() {
      $block.css("padding", $(this).val());
    });
  
    $(".text-color-input").on("input", function() {
      $block.css("color", $(this).val());
    });
  
    // Set initial button states based on current text alignment
    var currentAlignment = $block.css("text-align");
    $(".align-left").prop("disabled", currentAlignment === "left");
    $(".align-center").prop("disabled", currentAlignment === "center");
    $(".align-right").prop("disabled", currentAlignment === "right");
  
    // Bind click event handlers to update text alignment
    $(".align-left").on("click", function() {
      $block.css("text-align", "left");
      $(".align-left").prop("disabled", true);
      $(".align-center").prop("disabled", false);
      $(".align-right").prop("disabled", false);
    });
  
    $(".align-center").on("click", function() {
      $block.css("text-align", "center");
      $(".align-left").prop("disabled", false);
      $(".align-center").prop("disabled", true);
      $(".align-right").prop("disabled", false);
    });
  
    $(".align-right").on("click", function() {
      $block.css("text-align", "right");
      $(".align-left").prop("disabled", false);
      $(".align-center").prop("disabled", false);
      $(".align-right").prop("disabled", true);
    });
  
    if ($block.attr("id") === "grid-block") {
        options += '<input type="text" class="grid-template-columns-input" value="' + (defaultGridTemplateColumns ? defaultGridTemplateColumns[1] : '') + '" placeholder="Grid Template Columns">';
        options += '<input type="text" class="grid-template-rows-input" value="' + (defaultGridTemplateRows ? defaultGridTemplateRows[1] : '') + '" placeholder="Grid Template Rows">';
        options += '<select class="grid-flow-select">';
        options += '<option value="row" ' + (defaultGridFlow && defaultGridFlow[1] === "row" ? 'selected' : '') + '>Grid Flow: Row</option>';
        options += '<option value="column" ' + (defaultGridFlow && defaultGridFlow[1] === "column" ? 'selected' : '') + '>Grid Flow: Column</option>';
        options += '</select>';
        options += '<select class="justify-content-select">';
        options += '<option value="" selected>Justify Content</option>';
        options += '<option value="flex-start" ' + (defaultJustifyContent && defaultJustifyContent[1] === "flex-start" ? 'selected' : '') + '>Start</option>';
        options += '<option value="center" ' + (defaultJustifyContent && defaultJustifyContent[1] === "center" ? 'selected' : '') + '>Center</option>';
        options += '<option value="flex-end" ' + (defaultJustifyContent && defaultJustifyContent[1] === "flex-end" ? 'selected' : '') + '>End</option>';
        options += '<option value="space-between" ' + (defaultJustifyContent && defaultJustifyContent[1] === "space-between" ? 'selected' : '') + '>Space Between</option>';
        options += '<option value="space-around" ' + (defaultJustifyContent && defaultJustifyContent[1] === "space-around" ? 'selected' : '') + '>Space Around</option>';
        options += '<option value="space-evenly" ' + (defaultJustifyContent && defaultJustifyContent[1] === "space-evenly" ? 'selected' : '') + '>Space Evenly</option>';
        options += '</select>';
        options += '<select class="align-items-select">';
        options += '<option value="" selected>Align Items</option>';
        options += '<option value="flex-start" ' + (defaultAlignItems && defaultAlignItems[1] === "flex-start" ? 'selected' : '') + '>Start</option>';
        options += '<option value="center" ' + (defaultAlignItems && defaultAlignItems[1] === "center" ? 'selected' : '') + '>Center</option>';
        options += '<option value="flex-end" ' + (defaultAlignItems && defaultAlignItems[1] === "flex-end" ? 'selected' : '') + '>End</option>';
        options += '<option value="stretch" ' + (defaultAlignItems && defaultAlignItems[1] === "stretch" ? 'selected' : '') + '>Stretch</option>';
        options += '</select>';
        options += '<select class="place-content-select">';
        options += '<option value="" selected>Place Content</option>';
        options += '<option value="start" ' + (defaultPlaceContent && defaultPlaceContent[1] === "start" ? 'selected' : '') + '>Start</option>';
        options += '<option value="center" ' + (defaultPlaceContent && defaultPlaceContent[1] === "center" ? 'selected' : '') + '>Center</option>';
        options += '<option value="end" ' + (defaultPlaceContent && defaultPlaceContent[1] === "end" ? 'selected' : '') + '>End</option>';
        options += '<option value="space-between" ' + (defaultPlaceContent && defaultPlaceContent[1] === "space-between" ? 'selected' : '') + '>Space Between</option>';
        options += '<option value="space-around" ' + (defaultPlaceContent && defaultPlaceContent[1] === "space-around" ? 'selected' : '') + '>Space Around</option>';
        options += '<option value="space-evenly" ' + (defaultPlaceContent && defaultPlaceContent[1] === "space-evenly" ? 'selected' : '') + '>Space Evenly</option>';
        options += '<option value="stretch" ' + (defaultPlaceContent && defaultPlaceContent[1] === "stretch" ? 'selected' : '') + '>Stretch</option>';
        options += '</select>';
        options += '<button class="create-child-button">New Child</button>';
      }
      
      // Append the options to the block-options element
      $(".block-options").html(options);
      
      // Bind change event handlers to update style on input value change
      $(".font-size-input").on("input", function() {
        $block.css("font-size", $(this).val() + "px");
      });
      
      $(".background-color-input").on("input", function() {
        $block.css("background-color", $(this).val());
      });
      
      $(".width-input").on("input", function() {
        $block.css("width", $(this).val());
      });
      
      $(".height-input").on("input", function() {
        $block.css("height", $(this).val());
      });
      
      $(".margin-input").on("input", function() {
        $block.css("margin", $(this).val());
      });
      
      $(".padding-input").on("input", function() {
        $block.css("padding", $(this).val());
      });
      
      $(".text-color-input").on("input", function() {
        $block.css("color", $(this).val());
      });
      
      // Check if the parent element has the ID "grid-block"
      if ($block.attr("id") === "grid-block") {
        $(".grid-template-columns-input").on("input", function() {
          $block.css("grid-template-columns", $(this).val());
        });
      
        $(".grid-template-rows-input").on("input", function() {
          $block.css("grid-template-rows", $(this).val());
        });
      
        $(".grid-flow-select").on("change", function() {
          $block.css("grid-auto-flow", $(this).val());
        });
      
        $(".justify-content-select").on("change", function() {
          $block.css("justify-content", $(this).val());
        });
      
        $(".align-items-select").on("change", function() {
          $block.css("align-items", $(this).val());
        });
      
        $(".place-content-select").on("change", function() {
          $block.css("place-content", $(this).val());
        });
      }
      
  
    if ($block.attr("id") === "grid-block") {
      // Bind change event handlers to update grid-related styles on input value change
      $(".grid-template-columns-input").on("input", function() {
        $block.css("grid-template-columns", $(this).val());
      });
  
      $(".grid-template-rows-input").on("input", function() {
        $block.css("grid-template-rows", $(this).val());
      });
  
      $(".grid-flow-select").on("change", function() {
        $block.css("grid-auto-flow", $(this).val());
      });
  
      $(".justify-content-button").on("click", function() {
        $block.css("justify-content", "center");
        $(".justify-content-button").prop("disabled", true);
        $(".align-items-button").prop("disabled", false);
        $(".place-content-button").prop("disabled", false);
      });
  
      $(".align-items-button").on("click", function() {
        $block.css("align-items", "center");
        $(".justify-content-button").prop("disabled", false);
        $(".align-items-button").prop("disabled", true);
        $(".place-content-button").prop("disabled", false);
      });
  
      $(".place-content-button").on("click", function() {
        $block.css("place-content", "center");
        $(".justify-content-button").prop("disabled", false);
        $(".align-items-button").prop("disabled", false);
        $(".place-content-button").prop("disabled", true);
      });
    }

    $(".add-child").on("click", function() {
        var newChildElement = '<div class="block"></div>';
        $block.append(newChildElement);
        $block.children().last().click(function(event) {
          event.stopPropagation();
          BlockOptions(this);
        });
    });
  
    // Bind click event handler for creating a new child element
    $(".create-child-button").on("click", function() {
      var newChildElement = '<div class="block"></div>';
      $block.append(newChildElement);
      $block.children().last().click(function(event) {
        event.stopPropagation();
        BlockOptions(this);
      });
    });
  }
  
function insertBlockDialog(parent)
{
    $(".dialog").toggleClass("active");
    $.ajax({
        url: './jobs/pageEditor.php',
        type: 'POST',
        data: {
            getBlocks: 1
        },
        success: function (response) {
            try {
                var parsedResponse = JSON.parse(response);
                if (Array.isArray(parsedResponse)) {
                    parsedResponse.forEach(function (block) {
                        // Create a new child element
                        var childElement = $('<div>');

                        // Create a <p> or <h5> tag for each item in the parsed response array
                        var itemElement = $('<p>').text(block); // or $('<h5>').text(block);

                        // Append the item element to the child element
                        childElement.append(itemElement);

                        // Attach onclick event to the child element
                        childElement.click(function () {
                            getBlock(block);
                        });

                        // Append the child element to the specified parent element
                        $(".dialog").append(childElement);
                    });

                    // Make the blocks stackable inside the preview element
                } else {
                    console.log('Parsed response is not an array.');
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

function updateBlockPositions() {
    blockPositions = [];
    $(".preview .block").each(function () {
        blockPositions.push($(this).attr("id"));
    });
}

$(document).ready(function () {
    fetchBlocks('.editor-tools');
});
