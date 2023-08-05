<?php

$widget = new awtWidgets;

$incidentsCount = $widget->incidentsWidget();
$noticeCount = $widget->noticesWidget();
$theme = $widget->themesWidget();
$location = HOSTNAME . 'awt-content/plugins/awtDashboardWidgets/data/icons/';

?>


<div class="awt-widget-group">
    <div class="incidents-widget shadow">
        <div class="wrapper">
            <h3>Incidents</h3>
            <img src="<?php echo $location . "triangle-exclamation-solid.svg"; ?>">
        </div>
        <p><?php echo $incidentsCount; ?></p>
    </div>
    <div class="notices-widget shadow">
        <div class="wrapper">
            <h3>Notices</h3>
            <img src="<?php echo $location . "flag-regular.svg"; ?>">
        </div>
        <p><?php echo $noticeCount; ?></p>
    </div>
    <div class="plugins-widget shadow">
        <h3>Active plugins</h3>
        <p><?php echo $plugins = $widget->pluginStatsWidget() ?></p>
    </div>
    <div class="theme-widget shadow">
        <h3>Active Theme</h3>
        <p><?php echo $theme["name"] ?></p>
    </div>
</div>
<style>

    .awt-widget-group {
        display: flex;
        flex-wrap: wrap;
        width: 350px;
        gap: 10px;
    }

    .incidents-widget {
        background-color: red;
        height: 150px;
        width: 150px;
        padding: 10px;
        border-radius: 10px;
        color: #fff;
    }

    .incidents-widget .wrapper {
        display: grid;
        grid-template-columns: auto auto;
        grid-template-rows: auto;
        align-items: center;
        gap: 20px;
    }

    .incidents-widget .wrapper img {
        height: 30px;
        filter: var(--icon-filter);
    }

    .incidents-widget p {
        font-size: 25px;
        font-weight: 600;
    }

    .notices-widget {
        background-color: orange;
        height: 150px;
        width: 150px;
        padding: 10px;
        border-radius: 10px;
        color: #fff;
    }

    .notices-widget .wrapper {
        display: grid;
        grid-template-columns: auto auto;
        grid-template-rows: auto;
        align-items: center;
        gap: 20px;
    }

    .notices-widget .wrapper img {
        height: 30px;
        filter: var(--icon-filter);
    }

    .notices-widget p {
        font-size: 25px;
        font-weight: 600;
    }

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

    .theme-widget {
        background-color: lightsalmon;
        color: #fff;
        height: 150px;
        width: 150px;
        padding: 10px;
        border-radius: 10px;
    }

    .theme-widget p{
        font-weight: 600;
        font-size: 20px;
    }

</style>