<?php

$widget = new awtMetrics();

$visits = $widget->getMetricsTodayAll();
$uniqueToday = $widget->getMetricsTodayUnique();
$mostVisitedToday = $widget->getMostVisitedToday();

if($mostVisitedToday !== false && count($mostVisitedToday) != 0) {
    $mostVisitedToday = $mostVisitedToday[0]["url"];
} else {
    $mostVisitedToday = "Not enough data.";
}

?>

<div class="metrics-widgets-group">
    <div class="metrics-widget shadow">
        <h3>Number of visits today</h3>
        <p><?php echo $visits ?></p>
    </div>
    <div class="metrics-widget shadow">
        <h3>Number of unique visitors today</h3>
        <p><?php echo $uniqueToday ?></p>
    </div>
    <div class="metrics-widget shadow">
        <h3>Most visited page today</h3>
        <p><?php echo $mostVisitedToday ?></p>
    </div>
</div>

<style>

    .metrics-widgets-group {
        display: flex;
        flex-wrap: wrap;
        flex-direction: row;
        width: 350px;
        height: 400px;
        gap: 10px;
    }

    .metrics-widget {
        height: 150px;
        width: 150px;
        padding: 10px;
        border-radius: 10px;
    }

    .metrics-widget p {
        font-weight: 600;
        font-size: 25px;
    }

    .metrics-widget:nth-child(1) {
        background: orangered;
        color: #fff;
    }

    .metrics-widget:nth-child(2) {
        background: var(--secondary-color);
        color: #fff;
    }

    .metrics-widget:nth-child(3) {
        background: teal;
        color: #fff;
        width: 330px;
    }


</style>