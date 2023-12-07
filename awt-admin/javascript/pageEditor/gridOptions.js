function setGridOptions($block, defaultStyle) {
    var defaultGridTemplateColumns = defaultStyle ? defaultStyle.match(/grid-template-columns:\s*((?:[^;]+)*)/) : null;
    var defaultGridTemplateRows = defaultStyle ? defaultStyle.match(/grid-template-rows:\s*((?:[^;]+)*)/) : null;
  
    var defaultGridFlow = defaultStyle ? defaultStyle.match(/grid-auto-flow:\s*(\S+);/) : null;
    var defaultJustifyContent = defaultStyle ? defaultStyle.match(/justify-content:\s*(\S+);/) : null;
    var defaultAlignItems = defaultStyle ? defaultStyle.match(/align-items:\s*(\S+);/) : null;
    var defaultPlaceContent = defaultStyle ? defaultStyle.match(/place-content:\s*(\S+);/) : null;
    var defaultGap = defaultStyle ? defaultStyle.match(/gap:\s*(\S+);/) : null;
  
    var options = '<p>Grid options</p>';
    options += '<input type="text" class="grid-template-columns-input" value="' + (defaultGridTemplateColumns ? defaultGridTemplateColumns[1] : '') + '" placeholder="Grid Template Columns">';
    options += '<input type="text" class="grid-template-rows-input" value="' + (defaultGridTemplateRows ? defaultGridTemplateRows[1] : '') + '" placeholder="Grid Template Rows">';
    options += '<input type="text" class="grid-gap-input" value="' + (defaultGap ? defaultGap[1] : '') + '" placeholder="Gap">';
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
  
    $(".grid-gap-input").on("input", function () {
      $block.css("gap", $(this).val());
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