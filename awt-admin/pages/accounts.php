<?php
defined('DASHBOARD') or die("You should not do that..");
defined('ALL_CONFIG_LOADED') or die("An error has occured");

use admin\profiler;

$profiler = new profiler();
?>

<script src="./javascript/accounts/accounts.js"></script>
<script>
    $(document).ready(function() {
        fetchAccounts(".account-list");
    });
</script>

<link rel="stylesheet" href="./css/accounts.css">

<div class="info shadow hidden">

</div>

<div class="wrapper">
    <h3>Account List</h3>
    <div class="account-list">

    </div>
</div>


<?php if ($profiler->checkPermissions(1)) : ?>
    <div class="create-new-account shadow">
        <h3>Create new account</h3>
        <input type="text" autocomplete="false" class="fname" placeholder="First name" />
        <input type="text" autocomplete="false" class="lname" placeholder="Last name" />
        <input type="text" autocomplete="false" class="username" placeholder="Username" />
        <input type="email" autocomplete="false" class="email" placeholder="Email" />
        <input type="password" autocomplete="false" class="password" placeholder="Password" />
        <select name="permission" class="permissionLevel">
            <option value="0">Admin</option>
            <option value="1">Moderator</option>
            <option value="2" selected>Author</option>
        </select>
        <button type="button" onclick="createAccount('.account-list');">Create new account</button>
    </div>
<?php endif; ?>