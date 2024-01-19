
var $selection = $(".pageSection");

var shrinkenView = false;

var movingBlocks = false;

var selectedElement = false;

var ctrlPressed = false;

var floatingBlockSelectorActive = false;

var ignorePreviewClick = false;

var blockOptions = [];


function fetchBlocks(element, replacable = null, callback = null) {

  $(element).find('.category-container').remove();

  $.ajax({
    url: './jobs/pageEditor.php',
    type: 'POST',
    data: {
      getBlocks: 1
    },
    success: function (response) {

      const collections = JSON.parse(response);

      $.each(collections, function (key, value) {

        const collection = value;
        const categoryContainer = $('<div class="category-container"></div>');
        const categoryLabel = $('<h4>').text(collection.name);

        categoryLabel.addClass(collection.name.replace(/ /g, '-'));
        categoryLabel.append('<img class="blIcon" src="' + collection.iconURL + '"  alt="' + collection.name + '" />');
        categoryContainer.append(categoryLabel);

        collection.blocks.forEach(function (block, index) {

          const childElement = $('<div>').addClass('block-item hidden ' + collection.name.replace(/ /g, '-'));
          const itemElement = $('<p>').text(block.name);

          const itemHover = $("<div>").addClass('block-item-description shadow hidden');
          itemHover.append($("<h5>").text(block.name));
          itemHover.append($("<p>").text(block.description));

          childElement.append('<img class="blIcon" src="' + block.iconURL + '"  alt="' + collection.name + '" />');
          childElement.append(itemElement);

          childElement.append(itemHover);

          childElement.click(function () {
            getBlock(block.name, replacable);
            if (callback !== null) callback();
          });

          childElement.on("mouseenter", function () {
            var blockItemDescription = $(this).find('.block-item-description');
            blockItemDescription.removeClass("hidden");
          });

          childElement.on("mouseleave", function () {
            var blockItemDescription = $(this).find('.block-item-description');
            blockItemDescription.addClass("hidden");
          });

          categoryContainer.append(childElement);

        });
        $(element).append(categoryContainer);
        categoryLabel.click(function (catClass) {
          return function () {
            $(element + ' .category-container .block-item.' + catClass).toggleClass('hidden');
          }
        }(collection.name.replace(" ", "-")));
      });
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

      if (replacable !== null) {
        $(replacable).replaceWith(response);
      } else {
        $selection.append(response);
      }

      saveToHistory();

      hasTextChild($(".block")).attr("contenteditable", "true");

      $(".block").on("click", function (e) {
        BlockOptions($(this));
      }).children().on("click", function (e) {
        BlockOptions($(this));
        e.stopPropagation();
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
  var rgbValues = rgbColor.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
  if (!rgbValues) {
    return null; // Invalid RGB color string
  }

  var hexValues = rgbValues.slice(1).map(function (value) {
    var hex = parseInt(value, 10).toString(16);
    return hex.length === 1 ? "0" + hex : hex;
  });

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

  blockOptions.forEach((opt) => {
    opt.loadOption($block, defaultStyle);
  });

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

function findNearestElement(x, y) {
  let elements = $('.element');
  let nearestElement = null;
  let minDistance = Number.MAX_VALUE;

  elements.each(function () {
    let element = $(this);
    let offset = element.offset();
    let centerX = offset.left + element.width() / 2;
    let centerY = offset.top + element.height() / 2;

    let distance = Math.sqrt((x - centerX) ** 2 + (y - centerY) ** 2);

    if (distance < minDistance) {
      minDistance = distance;
      nearestElement = element;
    }
  });

  return nearestElement;
}

function contextMenu() {
  $(".context-menu").toggleClass("hidden");
  $(".context-menu").css('top', currentMousePos.y);
  $(".context-menu").css('left', currentMousePos.x);
  $(".context-menu *").bind("click.context", function () {
    $(".context-menu").toggleClass("hidden");
    $(".context-menu *").unbind("click.context");
  });
}


function publishContent(name) {
  var $pageSection = $('.pageSection');

  var $clonedPageSection = $pageSection.clone();

  $clonedPageSection.find(".block.empty.replacable").remove();
  $pageSection.find(".block.empty.replacable").remove();

  var htmlContent = $clonedPageSection.last().prop('outerHTML');

  $.ajax({
    url: './jobs/pageEditor.php',
    type: 'POST',
    data: {
      htmlContent: htmlContent,
      name: name,
      pageStatus: "live"
    },
    error: function (xhr, status, error) {
      console.log(error);
    }
  });
}


function savePage(name) {
  var $pageSection = $('.pageSection');

  var $clonedPageSection = $pageSection.clone();

  $clonedPageSection.find(".block.empty.replacable").remove();

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
      "overflow": 'auto', // Add overflow control
      "flex-grow": "0"
    });
    shrinkenView = true;
    $(caller).addClass("active");
  } else {
    $preview.css({
      "width": 'auto',
      "height": '100%',
      "border": 'none',
      "overflow": 'auto',
      "flex-grow": "1" // Reset overflow
    });
    shrinkenView = false;
    $(caller).removeClass("active");
  }
}

function detectEmpty() {
  $('.preview').find('.block').each(function (index, block) {
    if ($(block).children().length == 0 && $(block).text().trim().length == 0 && $(block).is("div")) {
      if ($(block).hasClass("empty") == false) $(block).addClass("empty");
    } else {
      $(block).removeClass("empty");
    }
  });
}

function createEditableLayout() {
  $selection = $('.pageSection');

  $(".preview").sortable({
    items: ".block",
    scroll: true,
    scrollSensitivity: 50,
    cursor: "move",
    helper: "clone",
    tolerance: "pointer",
    cancel: 'input,textarea,button,select,option,[contenteditable]',
    start: function (event, ui) {
      movingBlocks = true;
    },
    stop: function (event, ui) {
      movingBlocks = false;
      saveToHistory();
    }
  });

  $('.pageSection .block').each(function () {
    var $block = $(this);

    $block.on('click', function (e) {
      BlockOptions($block);
      $(document).find(":focus").each(function () {
        var focusedElement = $(this);
        if (!focusedElement.is($block) && !$block.has(focusedElement).length > 0) {
          $(this).blur();
        }
      });
    });

    hasTextChild($block).attr('contenteditable', 'true');

    $block.find('.block').on('click', function (e) {
      e.stopPropagation();
      BlockOptions($block);
    });
  });

  $(".preview").click(function () {
    if (ignorePreviewClick) {
      ignorePreviewClick = false;
      return;
    }

    $selection = $('.pageSection');

    $("*").removeClass('selected');

    $(".pageSection").addClass('selected');
    if ($selection.find(".selected").length === 0) selectedElement = true;
  });

  $(".pageSection").on("click", function (event) {
    // Check if the clicked element is a child of .pageSection
    if (
      $(event.target).closest(".pageSection").length > 0 &&
      !$(event.target).is(".preview")
    ) {
      ignorePreviewClick = true;
      return;
    }
    BlockOptions(this);
  });
}

$(document).ready(function () {

  createEditableLayout();

  $(".floating-blocks").on("DOMSubtreeModified", function (event) {
    if ($('.floating-blocks').hasClass('hidden')) {
      floatingBlockSelectorActive = false;
    } else {
      floatingBlockSelectorActive = true;
    }
  });

  $(".preview").on("DOMSubtreeModified", function (event) {
    detectEmpty();
  });

  saveToHistory();

  initShortcuts();

  fetchBlocks('.add-blocks');

  var find = $('*').filter(function () {
    return $(this).css('position') == 'fixed';
  });

  find.each(function () {
    $(this).css("position", "relative");
    $(this).css("top", "0");

  })
});


