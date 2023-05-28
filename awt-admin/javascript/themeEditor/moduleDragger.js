function moduleDragger(element_id, div_id) {
    // Make child elements of sections draggable within element_id
    $(element_id + ' section').find('*').each(function() {
      $(this).addClass('draggable');
    });
  
    // Make section names draggable within div_id
    $(element_id + ' section').each(function() {
      var sectionName = $(this).attr('class');
      $('<div>').addClass('draggable').text(sectionName).appendTo(div_id);
    });
  
    $(div_id).sortable({
      items: '.draggable',
      stop: function(event, ui) {
        var sortedDivs = $(div_id + ' > .draggable');
        sortedDivs.each(function(index) {
          var sectionName = $(this).text();
          var section = $(element_id + ' section.' + sectionName).detach();
          $(element_id).append(section);
        });
      }
    }).disableSelection();
  }
  