var $selection = $(".scene");
let shrinkenView = false;
let movingBlocks = false;
let selectedElement = false;
let ctrlPressed = false;
let floatingBlockSelectorActive = false;
let ignorePreviewClick = false;
let blockOptions = [];

function fetchBlocks(element, replacable = null, callback = null) {
  $(element).find('.category-container').remove();

  $.ajax({
    url: './jobs/pageEditor.php',
    type: 'POST',
    data: { getBlocks: 1 },
    success: function (response) {
      const collections = JSON.parse(response);

      $.each(collections, function (key, collection) {
        const categoryContainer = $('<div class="category-container"></div>');
        const categoryLabel = $('<h4>').text(collection.name);

        categoryLabel.addClass(collection.name.replace(/ /g, '-'));
        categoryLabel.append(`<img class="blIcon" src="${collection.iconURL}" alt="${collection.name}" />`);
        categoryContainer.append(categoryLabel);

        $.each(collection.blocks, function (key, block) {
          const childElement = $('<div>').addClass(`block-item hidden ${collection.name.replace(/ /g, '-')}`);
          const itemElement = $('<p>').text(block.name);

          const itemHover = $("<div>").addClass('block-item-description shadow hidden');
          itemHover.append($("<h5>").text(block.name));
          itemHover.append($("<p>").text(block.description));

          childElement.append(`<img class="blIcon" src="${block.iconURL}" alt="${collection.name}" />`);
          childElement.append(itemElement);
          childElement.append(itemHover);

          childElement.click(() => {
            getBlock(block.name, replacable);
            if (callback !== null) callback();
          });

          childElement.on("mouseenter", function () {
            $(this).find('.block-item-description').removeClass("hidden");
          });

          childElement.on("mouseleave", function () {
            $(this).find('.block-item-description').addClass("hidden");
          });

          categoryContainer.append(childElement);
        });

        $(element).append(categoryContainer);
        categoryLabel.click(() => {
          $(`${element} .category-container .block-item.${collection.name.replace(" ", "-")}`).toggleClass('hidden');
        });
      });
    },
    error: function (xhr, status, error) {
      console.error('AJAX request failed.');
      console.error(error);
    }
  });
}

function getBlock(name, replacable = null) {
  $.ajax({
    url: './jobs/pageEditor.php',
    type: 'POST',
    data: { getBlock: name },
    success: function (response) {
      if (replacable !== null) {
        $(replacable).replaceWith(response);
      } else {
        $selection.append(response);
      }

      createEditableLayout();
      
      $(".block").on("click", function (e) {
        BlockOptions($(this));
      }).children().on("click", function (e) {
        BlockOptions($(this));
        e.stopPropagation();
      });
    },
    error: function (xhr, status, error) {
      console.error('AJAX request failed.');
      console.error(error);
    }
  });
}

function rgbToHex(rgbColor) {
  if (rgbColor === null) return null;

  const rgbValues = rgbColor.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
  if (!rgbValues) return null;

  const hexValues = rgbValues.slice(1).map(value => {
    const hex = parseInt(value, 10).toString(16);
    return hex.length === 1 ? "0" + hex : hex;
  });

  return `#${hexValues.join("")}`;
}

function BlockOptions(element) {
  const $block = $(element);
  $('*').removeClass("selected");
  $block.addClass("selected");
  $selection = $block;
  const defaultStyle = $block.attr("style");

  setDefaultOptions($block, defaultStyle);

  blockOptions.forEach(opt => {
    opt.loadOption($block, defaultStyle);
  });

  $(".add-child, .create-child-button").off("click").on("click", function () {
    const newChildElement = '<div class="block"></div>';
    $block.append(newChildElement);
    $block.children().last().click(function (event) {
      event.stopPropagation();
      BlockOptions(this);
    });
  });
}

function findNearestElement(x, y) {
  const elements = $('.element');
  let nearestElement = null;
  let minDistance = Number.MAX_VALUE;

  elements.each(function () {
    const element = $(this);
    const offset = element.offset();
    const centerX = offset.left + element.width() / 2;
    const centerY = offset.top + element.height() / 2;

    const distance = Math.sqrt((x - centerX) ** 2 + (y - centerY) ** 2);

    if (distance < minDistance) {
      minDistance = distance;
      nearestElement = element;
    }
  });

  return nearestElement;
}

function contextMenu() {
  $(".context-menu").toggleClass("hidden").css({
    'top': currentMousePos.y,
    'left': currentMousePos.x
  });

  $(".context-menu *").bind("click.context", function () {
    $(".context-menu").toggleClass("hidden");
    $(".context-menu *").unbind("click.context");
  });
}

function publishContent(name) {
  const $pageSection = $('.scene');
  const $clonedPageSection = $pageSection.clone();

  $clonedPageSection.find(".block.empty.replacable").remove();
  $pageSection.find(".block.empty.replacable").remove();

  const htmlContent = $clonedPageSection.html();

  $.ajax({
    url: './jobs/pageEditor.php',
    type: 'POST',
    data: {
      htmlContent: htmlContent,
      name: name,
      pageStatus: "live"
    },
    error: function (xhr, status, error) {
      console.error(error);
    }
  });
}

function savePage(name) {
  const $pageSection = $('.scene');
  const $clonedPageSection = $pageSection.clone();

  $clonedPageSection.find(".block.empty.replacable").remove();
  const htmlContent = $clonedPageSection.html();

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
      console.error(error);
    }
  });
}

function changeViewPort(caller) {
  const $preview = $('.preview');

  if (!shrinkenView) {
    $preview.css({
      "width": '375px',
      "margin": 'auto auto',
      "height": '667px',
      "border": '2px solid #000',
      "overflow": 'auto',
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
      "flex-grow": "1"
    });
    shrinkenView = false;
    $(caller).removeClass("active");
  }
}

function detectEmpty() {
  $('.preview').find('.block').each(function (index, block) {
    const $block = $(block);

    if ($block.children().length === 0 && $block.text().trim().length === 0 && $block.is("div")) {
      if (!$block.hasClass("empty")) $block.addClass("empty");
    } else {
      $block.removeClass("empty");
    }
  });
}

function createEditableLayout() {
  $selection = $('.scene');

  $(".scene").sortable({
    items: "> .block",
    scroll: true,
    scrollSensitivity: 50,
    cursor: "move",
    helper: "clone",
    tolerance: "pointer",
    opacity: 0.5,
    cancel: 'input,textarea,button,select,option,[contenteditable]',
    start: function (event, ui) {
      movingBlocks = true;
    },
    stop: function (event, ui) {
      movingBlocks = false;
      saveToHistory();
    }
  });

  $(".scene .block").sortable({
    items: "> .block",
    scroll: false,
    cursor: "move",
    helper: "clone",
    tolerance: "pointer",
    opacity: 0.5,
    cancel: 'input, textarea, button, select, option, [contenteditable]',
    start: function (event, ui) {
      movingBlocks = true;
    },
    stop: function (event, ui) {
      movingBlocks = false;
      saveToHistory();
    }
  });

  $('.scene .block').each(function () {
    const $block = $(this);

    $block.on('click', function (e) {
      BlockOptions($block);
      e.stopPropagation();
      $(document).find(":focus").each(function () {
        const focusedElement = $(this);
        if (!focusedElement.is($block) && !$block.has(focusedElement).length > 0) {
          $(this).blur();
        }
      });
    });

    hasTextChild($block).attr('contenteditable', 'true');

    $block.find('.scene .block').on('click', function (e) {
      e.stopPropagation();
      BlockOptions($block);
    });
  });

  $(".scene").click(function () {
    if (ignorePreviewClick) {
      ignorePreviewClick = false;
      return;
    }

    $selection = $('.scene');

    $("*").removeClass('selected');

    $(".scene").addClass('selected');
    if ($selection.find(".selected").length === 0) selectedElement = true;
  });

  $(".scene").on("click", function (event) {
    if (
      $(event.target).closest(".scene").length > 0 &&
      !$(event.target).is(".scene")
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
    floatingBlockSelectorActive = $('.floating-blocks').hasClass('hidden') ? false : true;
  });

  $(".scene").on("DOMSubtreeModified", function (event) {
    detectEmpty();
  });

  saveToHistory();

  initShortcuts();

  fetchBlocks('.add-blocks');

  const find = $('*').filter(function () {
    return $(this).css('position') == 'fixed';
  });

  find.each(function () {
    $(this).css("position", "relative");
    $(this).css("top", "0");
  });
});
