function saveThemePage(name) {
  var $pageSection = $('.pageSection');

  var $clonedPageSection = $pageSection.clone();

  $clonedPageSection.find(".block.empty.replacable").remove();
  $pageSection.find(".block.empty.replacable").remove();

  var htmlContent = $clonedPageSection.last().prop('outerHTML');

  $.ajax({
    url: './jobs/themeEditor.php',
    type: 'POST',
    data: {
      htmlContent: htmlContent,
      name: name
    },
    success: function (response) {

    },
    error: function (xhr, status, error) {
      console.log(error);
    }
  });
}
