<?php

defined('DASHBOARD') or  die("You should not do that..");
defined('ALL_CONFIG_LOADED') or die("An error has occured");

use admin\{authentication, profiler};

$check = new authentication;

if (!$check->checkAuthentication()) {
  header("Location: ./login.php");
  exit();
}

$profiler = new profiler;


if(!$profiler->checkPermissions(2)) die("Insufficient permission to view this page!");

?>

<link rel="stylesheet" href="./css/mail.css">
<script src="./javascript/mail/mail.js"></script>

<div class="info hidden shadow">

</div>

<div class="dialog hidden shadow">
    <div class="header">
        <i class="fa-solid fa-circle-xmark" onclick="$('.dialog').addClass('hidden')"></i>
    </div>
    <div class="content">
        <input type="email" class="input" id="recipient" placeholder="Recipient">
        <input type="text" class="input" id="subject" placeholder="Subject">
        <textarea id="content" cols="30" rows="10" class="input" placeholder="Content"></textarea>
        <button class="button" onclick="sendMail()">Send</button>
    </div>
</div>

<section class="side-bar">
    <div class="account shadow">
        <h1>Mail account:</h1>
        <p><?php echo $profiler->email?></p>
        <h1>Sender name:</h1>
        <p><?php echo $profiler->firstname . " " . $profiler->lastname ?></p>
        <p class="important"><b>*IMPORTANT:</b> This page is only showing email that was sent from AWT and within AWT.</p>
        <p class="important">To send email from AWT you need to have email account hosted on same server as AWT is.</p>
        <button class="button" id="testMail"><i class="fa-solid fa-wifi"></i> Test mail server</button>
    </div>

    <div class="mail-selector shadow">
        <p data-status='2' class="selected" ><i class="fa-solid fa-inbox"></i> Inbox</p>
        <p data-status='1'><i class="fa-solid fa-envelope-circle-check"></i> Sent</p>
        <p data-status='0'><i class="fa-solid fa-triangle-exclamation"></i> Failed</p>
        <button class="button compose" id="green"><i class="fa-solid fa-pen-to-square"></i>Compose</button>
    </div>
</section>

<section class="mail-container shadow">
    <div class="header">
        <h2>Sent mail</h2>
    </div>
    <div class="mail-wrapper">

    </div>
</section>