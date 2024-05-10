<?php

defined('DASHBOARD') or die("You should not do that..");
defined('ALL_CONFIG_LOADED') or die("An error has occured");

use admin\{authentication, profiler};

$check = new authentication;

if (!$check->checkAuthentication()) {
    header("Location: ./login.php");
    exit();
}

$profiler = new profiler;

if (!$profiler->checkPermissions(2))
    die("Insufficient permission to view this page!");

?>

<script src="./javascript/accounts/accounts.js"></script>
<script src="./javascript/mail/mail.js"></script>

<script>
    $(document).ready(function () {
        fetchAccounts(".account-list");
    });
</script>

<link rel="stylesheet" href="./css/accounts.css">

<div class="info shadow hidden">

</div>


<div class="dialog hidden shadow">
    <div class="header">
        <i class="fa-solid fa-x" onclick="$('.dialog').addClass('hidden')"></i>
    </div>
    <div class="content">
        <input type="email" class="input" id="recipient" placeholder="Recipient" disabled>
        <input type="text" class="input" id="subject" placeholder="Subject">
        <textarea id="content" cols="30" rows="10" class="input" placeholder="Content"></textarea>
        <button class="button" onclick="sendMail()">Send</button>
    </div>
</div>

<div class="wrapper-big wrapper">
    <h3>Edit your account</h3>
    <div class="edit-account shadow">
        <input type="text" autocomplete="false" class="fname input" value="<?php echo $profiler->firstname ?>">
        <input type="text" autocomplete="false" class="lname input" value="<?php echo $profiler->lastname ?>">
        <input type="email" autocomplete="false" class="email input" value="<?php echo $profiler->email ?>">
        <input type="password" autocomplete="false" class="password input" placeholder="New password">
        <button type="button" class="button" id="green" onclick="changeInfo()">Change</button>
    </div>
</div>

<div class="wrapper">
    <h3>Account List</h3>
    <div class="account-list">

    </div>
</div>


<?php if ($profiler->checkPermissions(1)): ?>
    <div class="wrapper">
        <h3>Create new account</h3>
        <div class="create-new-account shadow">
            <input type="text" autocomplete="false" class="fname-create" placeholder="First name" />
            <input type="text" autocomplete="false" class="lname-create" placeholder="Last name" />
            <input type="text" autocomplete="false" class="username-create" placeholder="Username" />
            <input type="email" autocomplete="false" class="email-create" placeholder="Email" />
            <input type="password" autocomplete="false" class="password-create" placeholder="Password" />
            <select name="permission" class="permissionLevel-create">
                <option value="0">Admin</option>
                <option value="1">Moderator</option>
                <option value="2">Author</option>
            </select>
            <button type="button" onclick="createAccount('.account-list');" class="button">Create new account</button>
        </div>
    </div>

<?php endif; ?>