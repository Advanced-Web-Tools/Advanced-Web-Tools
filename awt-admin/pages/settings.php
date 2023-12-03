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
<script src="./javascript/settings/settings.js"></script>
<section>
  <div id="categoriesContainer" class="shadow">
    <h3>Categories</h3>
  </div>
  <div id="formsContainer"></div>
  <div class="site-health shadow">
    <h3>Health insight</h3>
    <div class="notices">
      <h3>Notices</h3>
      <p>
        <?php echo $siteHealth->numberOfNotices; ?>
      </p>
    </div>
    <div class="notices">
      <h3>Notices Today</h3>
      <p>
        <?php echo $siteHealth->numberOfNoticesToday; ?>
      </p>
    </div>
    <div class="incidents">
      <h3>Incidents</h3>
      <p>
        <?php echo $siteHealth->numberOfIncidents; ?>
      </p>
    </div>
    <div class="incidents">
      <h3>Incidents Today</h3>
      <p>
        <?php echo $siteHealth->numberOfIncidentsToday; ?>
      </p>
    </div>
    <div class="health <?php echo $health; ?>">
      <h3>Site health</h3>
      <p>
        <?php echo $health; ?>
      </p>
    </div>
    <div class="selector">
      <button class="button" id="red"
        onclick="switchView('.notices-overview', '.incidents-overview');">Incidents</button>
      <button class="button" onclick="switchView('.incidents-overview', '.notices-overview');">Notices</button>
    </div>
    <div class="incidents-overview hidden">
      <?php foreach ($siteHealth->incidents as $key => $value): ?>
        <div class="incident">
          <h4>
            <?php echo $value['caller']; ?>
          </h4>
          <p>
            <?php echo $value['content']; ?>
          </p>
          <p>
            <?php echo $value['time']; ?>
          </p>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="notices-overview hidden">
      <?php foreach ($siteHealth->notices as $key => $value): ?>
        <div class="notice">
          <h4>
            <?php echo $value['caller']; ?>
          </h4>
          <p>
            <?php echo $value['content']; ?>
          </p>
          <p>
            <?php echo $value['time']; ?>
          </p>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="updater">
      <h3>Update AWT</h3>
      <p id="current-version">Current version:
        <?php echo AWT_VERSION ?>
      </p>
      <p id='latest-version'>Latest version: N/A
        <span></span>
      </p>
      <button type="button" class="button" onclick="checkForUpdates(this);">Check for Updates</button>
      <button class="button update-button hidden" id="green" onclick="updateAwt(this);" type="button">Update AWT</button>
    </div>
  </div>
  <script>
    $(document).ready(function () {

      $.post('./jobs/settings.php', {
        name: 'getsettings'
      }, function (data) {

        const jsonData = JSON.parse(data);
        const categories = {};

        // Group elements by category
        jsonData.forEach(function (element) {
          let category = element.category;

          // Replace null category with "Miscellaneous"
          if (category === null) {
            category = "Miscellaneous";
          }

          if (!categories[category]) {
            categories[category] = [];
          }
          categories[category].push(element);
        });

        // Create divs for each category
        for (const category in categories) {
          const categoryDiv = $('<div class="category"></div>')
            .text(category)
            .attr('data-type-category', category === "Miscellaneous" ? "Miscellaneous" : category);
          $('#categoriesContainer').append(categoryDiv);

          categories[category].forEach(function (element) {
            var form = $('<form class="shadow hidden setting"></form>').attr('method', 'post').attr('action', './jobs/settings.php?name=' + element.name);
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
            form.attr('data-type-category', category === "Miscellaneous" ? "Miscellaneous" : category); // Assign data-type-category to the form
            $('#formsContainer').append(form); // Append the form to the formsContainer
          });
        }

        // Add click event to show/hide forms for the selected category
        $('.category').on('click', function () {
          $('.category').removeClass('selected');
          $(this).addClass('selected');

          const selectedCategory = $(this).attr('data-type-category');

          // Hide all forms outside formsContainer
          $('.setting').addClass('hidden');

          // Show forms for the selected category
          $('[data-type-category="' + selectedCategory + '"]').removeClass('hidden');
        });

        // Trigger click event on the first category by default
        $('.category:first').trigger('click');
      });
    });
  </script>
  </body>

  </html>
</section>