function saveChanges(name)
{
    var htmlContent = $('.preview').html();
    
    $.ajax({
      url: './jobs/themeEditor.php',
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