function createEmptyPage(inputName, link) {
  var name = $(inputName).val();
  console.log(name);
  $.ajax({
    url: './jobs/pages.php',
    type: 'POST',
    data: {
      createEmpty: true,
      name: name
    },
    success: function (response) {
      console.log(response);
      fetchPages('.pages', link); // Refresh the table after creating a new page
    },
    error: function (xhr, status, error) {
      console.log(error);
    }
  });
}

function deletePage(pageId, element) {
  $.ajax({
    url: './jobs/pages.php',
    type: 'POST',
    data: {
      deletePage: 1,
      id: pageId
    },
    success: function (response) {
      fetchPages(element); // Convert response to JSON array
    },
    error: function (xhr, status, error) {
    }
  });
}

function createTable(elementId, jsonData, link) {
  var tableContainer = $(elementId);
  tableContainer.empty();

  var excludedColumns = ['token', 'content_1', 'content_2', 'description'];

  var table = $('<div>').addClass('table');

  var headerRow = $('<div>').addClass('table-row shadow');

  var headerCell = $('<div>').addClass('table-cell header-cell').text('Edit Info');
  headerRow.append(headerCell);

  Object.keys(jsonData[0]).forEach(function (column) {
    if (!excludedColumns.includes(column)) {
      var headerCell = $('<div>').addClass('table-cell header-cell').text(column);
      headerRow.append(headerCell);
    }
  });


  var actionsHeader = $('<div>').addClass('table-cell header-cell').text('Actions');
  headerRow.append(actionsHeader);

  table.append(headerRow);

  jsonData.forEach(function (rowData) {

    const dataRow = $('<div>').addClass('table-row shadow');
    
    const modify = $('<i class="fa-solid fa-sliders"></i>');

    modify.attr('data-id', rowData.id);

    modify.on("click", function(e) {

      const id = $(this).attr('data-id');

      $('.input[data-id="' + id + '"]').toggleClass('hidden');
      $('.select[data-id="' + id + '"]').toggleClass('hidden');

      $(this).parent().toggleClass("modify");
    });

    dataRow.append(modify);

    console.log(rowData);

    const editName = $("<input type='text' class='input hidden' placeholder='Page name'>").val(rowData.name);
    const editDescription = $("<textarea class='input hidden' placeholder='Description'>").val(rowData.description);
    const changeStatus = $('<select class="select hidden">');

    editName.attr('data-id', rowData.id);
    editName.attr('data-change', 'name');
    editDescription.attr('data-id', rowData.id);
    editDescription.attr('data-change', 'description');
    changeStatus.attr('data-id', rowData.id);
    changeStatus.attr('data-change', 'status');


    editName.on("change", function(e) {

      const id = $(this).attr('data-id');
      const change = $(this).attr('data-change');
      const value = $(this).val();

      changeInfo(elementId, link, id, change, value);

    });

    editDescription.on("change", function(e) {

      const id = $(this).attr('data-id');
      const change = $(this).attr('data-change');
      const value = $(this).val();

      changeInfo(elementId, link, id, change, value);

    });

    changeStatus.on("change", function(e) {

      const id = $(this).attr('data-id');
      const change = $(this).attr('data-change');
      const value = $(this).val();

      changeInfo(elementId, link, id, change, value);

    });

    if(rowData.status === 'live'){
      changeStatus.append("<option value='live'>Live</option>");
      changeStatus.append("<option value='preview'>Preview</option>");
    } else {
      changeStatus.append("<option value='preview'>Preview</option>");
      changeStatus.append("<option value='live'>Live</option>");
    }
    
    Object.entries(rowData).forEach(function ([column, value]) {
      if (!excludedColumns.includes(column)) {
        const dataCell = $('<div>').addClass('table-cell data-cell');
        dataCell.text(value);
        dataRow.append(dataCell);
      }
    });

    
    // Add hyperlink and button to the row
    var pageName = rowData.name;
    var pageId = rowData.id;
    var token = rowData.token;
    var editLink = $('<a>').attr('href', '?page=Page Editor&editPage=' + pageId + '&pageName=' + pageName).text('Edit');
    var liveLink = $('<a>').attr('href', link + '?page=' + pageName + "&custom").text('Visit');
    var previewLink = $('<a>').attr('href', link + '?page=' + pageName + "&custom&preview=" + token).text('Preview');
    editLink.attr('target', "_blank");
    liveLink.attr('target', "_blank");
    previewLink.attr('target', "_blank");
    editLink.attr('rel', "noreferrer");
    liveLink.attr('rel', "noreferrer");
    previewLink.attr('rel', "noreferrer");
    var deleteButton = $('<button id="red">').text('Delete');
    deleteButton.addClass('button')
    deleteButton.on('click', function () {
      deletePage(pageId, elementId);
    });
    
    var actionsCell = $('<div>').addClass('table-cell actions-cell');
    actionsCell.append(editLink, liveLink, previewLink, deleteButton);
    dataRow.append(actionsCell);
    dataRow.append('<span>');
    dataRow.append('<span>');
    dataRow.append(editName);
    dataRow.append(changeStatus);
    dataRow.append('<span>');
    dataRow.append(editDescription);


    table.append(dataRow);
  });

  tableContainer.append(table);
}

function fetchPages(element, link) {
  $.ajax({
    url: './jobs/pages.php',
    type: 'POST',
    data: {
      getPages: 1
    },
    success: function (response) {
      createTable(element, JSON.parse(response), link); // Convert response to JSON array
    },
    error: function (xhr, status, error) {
      console.log(error);
    }
  });
}

$(document).ready(function () {
  fetchPages('.pages');
});

function changeInfo(element, link, id, change, value)
{
  
  $.ajax({
    url: './jobs/pages.php',
    type: 'POST',
    data: {
      update: id,
      change: change,
      value, value
    },
    success: function (response) {
      console.log(response);
      fetchPages(element, link); // Convert response to JSON array
    },
    error: function (xhr, status, error) {
      console.log(error);
    }
  });
}