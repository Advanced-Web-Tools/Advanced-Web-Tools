function checkForUpdates(caller) {
    $.ajax({
        url: './jobs/updateAwt.php',
        type: 'POST',
        data: {
            versionCompare: 1
        },
        success: function (response) {
          $(caller).html('Checking... <i class="fa-solid fa-spinner fa-spin-pulse"></i>');
        },
        error: function (xhr, status, error) {
          console.log(error);
        }
    }).done(function(response) {
      const data = JSON.parse(response);

      $(caller).html('Checked! <i class="fa-regular fa-circle-check"></i>');
      $(caller).attr('disabled', true);
      $("p#latest-version").html("Latest version: " + data.latest + "<span></span>");

      if(data.version_compare == -1){
        $('.update-button').removeClass('hidden');
        $(caller).addClass('hidden');
        $("p#latest-version span").html("<p>Updates available.</p>");
      } else {
        $("p#latest-version span").html("<p>You are on the latest version of AWT.</p>");
      }
    });
}

function updateAwt(caller) {
    $(caller).html("Updating.. ");
    $(caller).append('<i class="fa-solid fa-spinner fa-spin-pulse"></i>');
    $.ajax({
        url: './jobs/updateAwt.php',
        type: 'POST',
        data: {
            updateAwt: 1
        },
        success: function (response) {
            $(caller).html("Updating.. ");
            $(caller).append('<i class="fa-solid fa-spinner fa-spin-pulse"></i>');
        },
        error: function (xhr, status, error) {
          console.log(error);
        }
    }).done(function(response) {
        alert(response + "\nPage will reload now..");
        window.location.reload();
    });
}