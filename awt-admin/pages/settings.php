<?php

defined('DASHBOARD') or die("You should not do that..");
defined('ALL_CONFIG_LOADED') or die("An error has occured");

use admin\authentication;
use settings\settings;

$check = new authentication;

if (!$check->checkAuthentication()) {
    header("Location: ./login.php");
    exit();
}


?>

<section>
<div id="formsContainer"></div>

  <script>
$(document).ready(function() {
      // Send a POST request to retrieve the JSON data
      $.post('./jobs/settings.php', { name: 'getsettings' }, function(data) {
        // Parse the JSON response
        const jsonData = JSON.parse(data);

        // Iterate through each element in the JSON data
        jsonData.forEach(function(element) {
          // Create a form for each element
          var form = $('<form></form>').attr('method', 'post').attr('action', './jobs/settings.php?name=' + element.name);
          var h3 = $('<h4></h4>').text(element.name.replace(/_/g, ' '));
          var appliedWhenInput = $('<input type="text" name="applied_when" placeholder="Applied when"/>').val(element.applied_when);
          var valueInput;

          // Create a checkbox for 'true' or 'false' values
          if (element.value === 'true' || element.value === 'false') {
            valueInput = $('<input type="checkbox" name="value" id="' + element.name + '" />').prop('checked', element.value === 'true');
            form.append(valueInput);
          }
          // Create a textbox for string values
          else {
            valueInput = $('<input type="text" name="value" />').val(element.value);
            
          }

          // Create a submit button for the form
          var submitButton = $('<input type="submit" value="Submit" />');

          // Append the form elements to the form
          form.append(h3, appliedWhenInput, valueInput, submitButton);

          // Append the form to the forms container
          $('#formsContainer').append(form);
        });
      });
    });
    
  </script>
</body>
</html>
</section>