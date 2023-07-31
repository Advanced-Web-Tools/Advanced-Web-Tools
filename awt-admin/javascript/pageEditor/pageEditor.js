
var $selection = $(".pageSection");

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
          $('.pageSection').children().each(function () {
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
  console.log($selection);
  $.ajax({
    url: './jobs/pageEditor.php',
    type: 'POST',
    data: {
      getBlock: name
    },
    success: function (response) {
      console.log('AJAX request succeeded.');
      $selection.append(response);

      // Make all elements with class "block" sortable within .pageSection
      $(".pageSection").sortable({
        items: ".block",
        cancel: 'input,textarea,button,select,option,[contenteditable]',
        update: function (event, ui) {
          // Update the block positions array
          updateBlockPositions();
        }
      });

      // Make each text element within .block editable
      hasTextChild($(".block")).attr("contenteditable", "true");

      // Attach BlockOptions function to click event of block and its direct children
      $(".block").on("click", function () {
        BlockOptions($(this));
      }).children().on("click", function (e) {
        e.stopPropagation(); // Prevent event bubbling to the parent block
      });

      // Check if the parent element has the ID "grid-block"
      if ($("#grid-block").length > 0) {
        // Make the grid elements sortable within the grid block
        $(".block#grid-block").sortable({
          items: "> .block",
          handle: ".handle",
          update: function (event, ui) {
            // Update the grid positions array
            updateGridPositions();
          }
        });
      }
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


function setDefaultOptions($block, defaultStyle) {
  var defaultMargin = defaultStyle ? defaultStyle.match(/margin:\s*((?:[^;]+)*)/) : null;
  var defaultPadding = defaultStyle ? defaultStyle.match(/padding:\s*((?:[^;]+)*)/) : null;
  var defaultWidth = defaultStyle ? defaultStyle.match(/width:\s*((?:[^;]+)*)/) : null;
  var defaultHeight = defaultStyle ? defaultStyle.match(/height:\s*((?:[^;]+)*)/) : null;
  var defaultBackgroundColor = defaultStyle ? defaultStyle.match(/background:\s*([^;]+);/) : null;
  defaultBackgroundColor = defaultBackgroundColor ? defaultBackgroundColor[1] : null;
  defaultBackgroundColor = rgbToHex(defaultBackgroundColor);
  var options = '<p>Block options:</p>';
  options += '<input type="text" class="margin-input" value="' + (defaultMargin ? defaultMargin[1] : '') + '" placeholder="Margin">';
  options += '<input type="text" class="padding-input" value="' + (defaultPadding ? defaultPadding[1] : '') + '" placeholder="Padding">';
  options += '<input type="text" class="width-input" value="' + (defaultWidth ? defaultWidth[1] : '') + '" placeholder="Width">';
  options += '<input type="text" class="height-input" value="' + (defaultHeight ? defaultHeight[1] : '') + '" placeholder="Height">';
  options += '<label for="background-color">Background color:</label>';
  options += '<input type="color" class="background-color-input" id="background-color" value="' + (defaultBackgroundColor ? defaultBackgroundColor : '') + '" placeholder="Background Color">';
  options += '<button class="parent-selection">Select Parent</button>';
  options += '<button class="delete-block">Delete Block</button>';
  $(".block-options").html(options);

  $(".margin-input").on("input", function () {
    $block.css("margin", $(this).val());
  });

  $(".padding-input").on("input", function () {
    $block.css("padding", $(this).val());
  });

  $(".width-input").on("input", function () {
    $block.css("width", $(this).val());
  });

  $(".height-input").on("input", function () {
    $block.css("height", $(this).val());
  });

  $(".background-color-input").on("input", function () {
    $block.css("background", $(this).val());
  });

  $(".parent-selection").on("click", function () {
    BlockOptions($block.parent());
  });

  $(".delete-block").on("click", function () {
    $selection = $block.closest().parent();
    $block.remove();
  });

}

function setGridOptions($block, defaultStyle) {
  var defaultGridTemplateColumns = defaultStyle ? defaultStyle.match(/grid-template-columns:\s*((?:[^;]+)*)/) : null;
  var defaultGridTemplateRows = defaultStyle ? defaultStyle.match(/grid-template-rows:\s*((?:[^;]+)*)/) : null;

  var defaultGridFlow = defaultStyle ? defaultStyle.match(/grid-auto-flow:\s*(\S+);/) : null;
  var defaultJustifyContent = defaultStyle ? defaultStyle.match(/justify-content:\s*(\S+);/) : null;
  var defaultAlignItems = defaultStyle ? defaultStyle.match(/align-items:\s*(\S+);/) : null;
  var defaultPlaceContent = defaultStyle ? defaultStyle.match(/place-content:\s*(\S+);/) : null;

  var options = '<p>Grid options</p>';
  options += '<input type="text" class="grid-template-columns-input" value="' + (defaultGridTemplateColumns ? defaultGridTemplateColumns[1] : '') + '" placeholder="Grid Template Columns">';
  options += '<input type="text" class="grid-template-rows-input" value="' + (defaultGridTemplateRows ? defaultGridTemplateRows[1] : '') + '" placeholder="Grid Template Rows">';
  options += '<select class="grid-flow-select">';
  options += '<option value="row" ' + (defaultGridFlow && defaultGridFlow[1] === "row" ? 'selected' : '') + '>Grid Flow: Row</option>';
  options += '<option value="column" ' + (defaultGridFlow && defaultGridFlow[1] === "column" ? 'selected' : '') + '>Grid Flow: Column</option>';
  options += '</select>';
  options += '<select class="justify-content-select">';
  options += '<option value="" selected>Justify Content</option>';
  options += '<option value="start" ' + (defaultJustifyContent && defaultJustifyContent[1] === "start" ? 'selected' : '') + '>Start</option>';
  options += '<option value="center" ' + (defaultJustifyContent && defaultJustifyContent[1] === "center" ? 'selected' : '') + '>Center</option>';
  options += '<option value="end" ' + (defaultJustifyContent && defaultJustifyContent[1] === "end" ? 'selected' : '') + '>End</option>';
  options += '</select>';
  options += '<select class="align-items-select">';
  options += '<option value="" selected>Align Items</option>';
  options += '<option value="center" ' + (defaultAlignItems && defaultAlignItems[1] === "center" ? 'selected' : '') + '>Center</option>';
  options += '<option value="stretch" ' + (defaultAlignItems && defaultAlignItems[1] === "stretch" ? 'selected' : '') + '>Stretch</option>';
  options += '</select>';
  options += '<select class="place-content-select">';
  options += '<option value="" selected>Place Content</option>';
  options += '<option value="start" ' + (defaultPlaceContent && defaultPlaceContent[1] === "start" ? 'selected' : '') + '>Start</option>';
  options += '<option value="center" ' + (defaultPlaceContent && defaultPlaceContent[1] === "center" ? 'selected' : '') + '>Center</option>';
  options += '<option value="end" ' + (defaultPlaceContent && defaultPlaceContent[1] === "end" ? 'selected' : '') + '>End</option>';
  options += '</select>';
  options += '<button class="create-child-button">New Child</button>';

  $(".block-options").append(options);

  $(".grid-template-columns-input").on("input", function () {
    $block.css("grid-template-columns", $(this).val());
  });

  $(".grid-template-rows-input").on("input", function () {
    $block.css("grid-template-rows", $(this).val());
  });

  $(".grid-flow-select").on("change", function () {
    $block.css("grid-auto-flow", $(this).val());
  });

  $(".justify-content-select").on("change", function () {
    $block.css("justify-content", $(this).val());
  });

  $(".align-items-select").on("change", function () {
    $block.css("align-items", $(this).val());
  });

  $(".place-content-select").on("change", function () {
    $block.css("place-content", $(this).val());
  });
}

function setTextOptions($block, defaultStyle) {
  var defaultFontSize = defaultStyle ? defaultStyle.match(/font-size:\s*(\S+);/) : null;
  var defaultTextColor = defaultStyle ? defaultStyle.match(/color:\s*([^;]+);/) : null;

  // Extract the first match from the array if available
  defaultFontSize = defaultFontSize ? defaultFontSize[1].replace('px', '') : 15;
  defaultTextColor = defaultTextColor ? defaultTextColor[1] : null;
  defaultTextColor = rgbToHex(defaultTextColor);

  var options = '<p>Text options:</p>';
  options += '<input type="number" class="font-size-input" value="' + (defaultFontSize ? defaultFontSize : '') + '" placeholder="Font Size">';
  options += '<label for="text-color">Text color:</label>';
  options += '<input type="color" class="text-color-input" id="text-color" value="' + (defaultTextColor ? defaultTextColor : '') + '" placeholder="Text Color">';
  options += '<div class="alignment-buttons"><button class="align-left"><i class="fa-solid fa-align-left"></i></button>';
  options += '<button class="align-center"><i class="fa-solid fa-align-center"></i></button>';
  options += '<button class="align-right"><i class="fa-solid fa-align-right"></i></button></div>';

  $(".block-options").append(options);

  $(".font-size-input").on("input", function () {
    $selection.css("font-size", $(this).val() + "px");
  });

  $(".text-color-input").on("input", function () {
    $selection.css("color", $(this).val());
  });

  // Set initial button states based on current text alignment
  var currentAlignment = $block.css("text-align");
  $(".align-left").prop("disabled", currentAlignment === "left");
  $(".align-left").prop("disabled", currentAlignment === "");
  $(".align-center").prop("disabled", currentAlignment === "center");
  $(".align-right").prop("disabled", currentAlignment === "right");

  // Bind click event handlers to update text alignment
  $(".align-left").on("click", function () {
    $block.css("text-align", "left");
    $(".align-left").prop("disabled", true);
    $(".align-center").prop("disabled", false);
    $(".align-right").prop("disabled", false);
  });

  $(".align-center").on("click", function () {
    $block.css("text-align", "center");
    $(".align-left").prop("disabled", false);
    $(".align-center").prop("disabled", true);
    $(".align-right").prop("disabled", false);
  });

  $(".align-right").on("click", function () {
    $block.css("text-align", "right");
    $(".align-left").prop("disabled", false);
    $(".align-center").prop("disabled", false);
    $(".align-right").prop("disabled", true);
  });
}

function ListOptions($block, defaultStyle) {
  var defaultListStyle = defaultStyle ? defaultStyle.match(/list-style:\s*(\S+);/) : null;
  defaultListStyle = defaultListStyle ? defaultListStyle[1] : "none";

  var options = '<p>List options:</p>';
  options += '<select class="list-style-select">';
  options += '<option value="none">None</option>';
  options += '<option value="ordered">Ordered</option>';
  options += '<option value="unordered">Unordered</option>';
  options += '</select>';
  options += '<button class="add-list-item">Add Item</button>';
  options += '<button class="remove-list-item">Remove Item</button>';

  $(".block-options").append(options);

  // Set the default list style option
  $(".list-style-select").val(defaultListStyle);

  // Event handler for list style selection change
  $(".list-style-select").on("change", function () {
    var selectedOption = $(this).val();
    if (selectedOption === "ordered") {
      $block.css("list-style", "ordered");
    } else if (selectedOption === "unordered") {
      $block.css("list-style", "unordered");
    } else {
      $block.css("list-style", "none");
    }
  });

  // Event handler for adding a new list item
  $(".add-list-item").on("click", function () {
    var newListElement = '<li contenteditable="true">New List Item</li>';
    $block.append(newListElement);
  });

  $(".remove-list-item").on("click", function () {
    $block.children("li").last().remove();
  });
}


function hasTextChild($block) {
  var allowedTags = ["p", "h1", "h2", "h3", "h4", "h5", "h6", "a", "strong", "em", "b", "i", "u", "li"];

  return $block
    .find("*")
    .filter(function() {
      var tagName = this.tagName.toLowerCase();
      return allowedTags.includes(tagName) || this.nodeType === 3 && $.trim(this.nodeValue).length > 0;
    });
}

function hasListChild($block) {
  return $block.find(":not(:has(*))").filter(function () {
    var listTags = ["ul", "ol", "li", "dl", "dt", "dd"];
    return listTags.includes(this.tagName.toLowerCase());
  });
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

  $block.sortable({
    items: "> .block",
    handle: ".handle",
    update: function (event, ui) {
      // Update the block positions array
      updateBlockPositions();
    }
  });
}

function updateBlockPositions() {
  blockPositions = [];
  $(".pageSection .block").each(function () {
    blockPositions.push($(this).attr("id"));
  });
}

function publishContent(name) {
  var htmlContent = $('.pageSection').html();
    
  $.ajax({
    url: './jobs/pageEditor.php',
    type: 'POST',
    data: {
      htmlContent: htmlContent,
      name : name,
      pageStatus: "live"
    },
    success: function(response) {
      console.log('AJAX request succeeded.');
      console.log(response);
    },
    error: function(xhr, status, error) {
      console.log('AJAX request failed.');
      console.log(error);
    }
  });
}

function publishContentPreview(name) {
  var htmlContent = $('.pageSection').html();
    
  $.ajax({
    url: './jobs/pageEditor.php',
    type: 'POST',
    data: {
      htmlContent: htmlContent,
      name : name,
      pageStatus: "preview"
    },
    success: function(response) {
      console.log('AJAX request succeeded.');
      console.log(response);
    },
    error: function(xhr, status, error) {
      console.log('AJAX request failed.');
      console.log(error);
    }
  });
}

$(document).ready(function () {
  var ignorePreviewClick = false;
  $selection = $('.pageSection');

  $(".pageSection").sortable({
    items: ".block",
    cancel: 'input,textarea,button,select,option,[contenteditable]',
    update: function (event, ui) {
      // Update the block positions array
      updateBlockPositions();
    }
  });

  $('.pageSection .block').each(function() {
    var $block = $(this);
  
    $block.on('click', function() {
      BlockOptions($block);
    });
    
    hasTextChild($block).attr('contenteditable', 'true');

    $block.find('.block').on('click', function(e) {
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

  fetchBlocks('.editor-tools');
});



