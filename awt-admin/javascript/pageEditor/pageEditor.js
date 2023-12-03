
var $selection = $(".pageSection");

function fetchBlocks(element) {
  $.ajax({
    url: './jobs/pageEditor.php',
    type: 'POST',
    data: {
      getBlocks: 1
    },
    success: function (response) {
      try {
        var parsedResponse = JSON.parse(response);
        console.log(parsedResponse);

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
                  getBlock(block.name);
                });

                // Append the child element to the category container
                categoryContainer.append(childElement);
              });

              // Append the category container to the specified parent element
              $(element).append(categoryContainer);

              // Add a click event to toggle the visibility of child elements
              categoryLabel.click(function (catClass) {
                return function () {
                  console.log(catClass);
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
  var $pageSection = $('.pageSection');
  var htmlContent = $pageSection.last().prop('outerHTML');
  $.ajax({
    url: './jobs/pageEditor.php',
    type: 'POST',
    data: {
      htmlContent: htmlContent,
      name: name,
      pageStatus: "live"
    },
    success: function (response) {
    },
    error: function (xhr, status, error) {
      console.log(error);
    }
  });
}


function publishContentPreview(name) {
  var $pageSection = $('.pageSection');
  var htmlContent = $pageSection.prop('outerHTML');

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
    detectEmpty();
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
    detectEmpty();
    BlockOptions(this);
    fetchBlocks('.editor-tools');
  });

  fetchBlocks('.editor-tools');
});

var shrinkenView = false;

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
  $('.pageSection').find('.block').each(function (index, block) {
    if ($(block).children().length == 0 && $(block).text().trim().length == 0 && $(block).is("div")) {
      if($(block).hasClass("empty") == false) $(block).addClass("empty");
    } else {
      $(block).removeClass("empty");
    }
  });
}

$(document).ready(function () {
  detectEmpty();
});
