<?php

defined('DASHBOARD') or die("You should not do that..");
defined('ALL_CONFIG_LOADED') or die("An error has occured");

use settings\siteHealth;

$siteHealth = new siteHealth;

$health = $siteHealth->getHealth();

?>
<script>
  function switchView(oldContent, newContent) {
    $(oldContent).addClass("hidden");
    $(newContent).removeClass("hidden");
  }
</script>
<link rel="stylesheet" href="./css/settings.css">
<section>

  <div id="formsContainer">
  </div>
  <div class="site-health shadow">
    <div class="notices">
      <h3>Notices</h3>
      <p><?php echo $siteHealth->numberOfNotices; ?></p>
    </div>
    <div class="notices">
      <h3>Notices Today</h3>
      <p><?php echo $siteHealth->numberOfNoticesToday; ?></p>
    </div>
    <div class="incidents">
      <h3>Incidents</h3>
      <p><?php echo $siteHealth->numberOfIncidents; ?></p>
    </div>
    <div class="incidents">
      <h3>Incidents Today</h3>
      <p><?php echo $siteHealth->numberOfIncidentsToday; ?></p>
    </div>
    <div class="health <?php echo $health; ?>">
      <h3>Site health</h3>
      <p><?php echo $health; ?></p>
    </div>
    <div class="selector">
      <button class="button" onclick="switchView('.notices-overview', '.incidents-overview');">Incidents</button>
      <button class="button" onclick="switchView('.incidents-overview', '.notices-overview');">Notices</button>
    </div>
    <div class="incidents-overview hidden">
      <?php foreach ($siteHealth->incidents as $key => $value) : ?>
        <div class="incident">
          <h4><?php echo $value['caller']; ?></h4>
          <p><?php echo $value['content']; ?></p>
          <p><?php echo $value['time']; ?></p>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="notices-overview hidden">
      <?php foreach ($siteHealth->notices as $key => $value) : ?>
        <div class="notice">
          <h4><?php echo $value['caller']; ?></h4>
          <p><?php echo $value['content']; ?></p>
          <p><?php echo $value['time']; ?></p>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="updater">
        <h3>Check for updates</h3>
        <p>Current version: <?php echo AWT_VERSION ?></p>
        <a class="button" href="./jobs/updateAwt.php">Update AWT</a>
    </div>
  </div>
  <script>
    $(document).ready(function() {

      $.post('./jobs/settings.php', {
        name: 'getsettings'
      }, function(data) {

        const jsonData = JSON.parse(data);

        jsonData.forEach(function(element) {

          var form = $('<form class="shadow"></form>').attr('method', 'post').attr('action', './jobs/settings.php?name=' + element.name);
          var h4 = $('<h4></h4>').text(element.name.replace(/_/g, ' '));
          var valueInput = $("<div></div>");

          if (element.value === 'true' || element.value === 'false') {
            inputLabel = $('<label class="switch"></label>');
            input = $('<input type="checkbox" name="value" id="' + element.name + '" />').prop('checked', element.value === 'true');
            inputLabel.append(input);
            span = $('<span class="slider round"></span>');
            inputLabel.append(span);
            valueInput.append(inputLabel);
          } else {
            valueInput = $('<input type="text" class="input" name="value" />').val(element.value);
          }

          var submitButton = $('<input type="submit" class="button" value="Change" />');

          form.append(h4, valueInput, submitButton);

          $('#formsContainer').append(form);
        });
      });
    });
  </script>
  </body>

  </html>
</section>