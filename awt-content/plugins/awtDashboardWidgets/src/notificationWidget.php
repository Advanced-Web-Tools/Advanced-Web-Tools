<?php
$widget = new awtWidgets();
?>

<div class="notification-widget shadow">
    <h3>Notifications</h3>
    <div class="wrapper">
        <?php echo $notifications = $widget->notificationWidget(10) ?>
    </div>
</div>

<style>

    .notification-widget {
        width: 300px;
        height: 90%;
        border-radius: 10px;
        padding: 10px;
        overflow-y: auto;
    }

    .notification-widget .wrapper {
        display: grid;
        grid-template-columns: 100%;
        gap: 10px;
    }

    .notification-widget .notification {
        border-radius: 10px;
        padding: 5px;
    }

    .notification-widget .notification h4 {
        margin: 5px 0 0 0;
    }

    .notification-widget .notification p {
        margin: 5px;
    }

    .notification-widget .notification.low {
        background-color: green;
        color: #fff;
    }

    .notification-widget .notification.medium {
        background-color: yellow;
    }

    .notification-widget .notification.high {
        background-color: red;
        color: #fff;
    }

</style>