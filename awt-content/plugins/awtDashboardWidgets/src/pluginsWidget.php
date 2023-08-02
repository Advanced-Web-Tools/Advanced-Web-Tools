<?php
$widget = new awtWidgets();
?>

<div class="plugins-widget shadow">
    <h3>Active plugins</h3>
    <p><?php echo $notifications = $widget->pluginStatsWidget() ?></p>
</div>

<style>
    .plugins-widget {
        background-color: cornflowerblue;
        color: #fff;
        height: 150px;
        width: 150px;
        padding: 10px;
        border-radius: 10px;
    }

    .plugins-widget p{
        font-weight: 600;
        font-size: 25px;
    }

</style>