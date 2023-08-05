<?php
$widget = new awtWidgets;
$pages = $widget->pagesWidget();
?>

<div class="pages-widget shadow">
    <h3>Page list</h3>
    <?php foreach ($pages as $page) : ?>
        <a href="<?php echo HOSTNAME; ?>?custom&page=<?php echo $page; ?>" target="_blank"><?php echo $page; ?></a>
    <?php endforeach; ?>
</div>

<style>
    .pages-widget {
        width: 330px;
        height: 360px;
        padding: 10px;
        background: #fff;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
    }

    .pages-widget a {
        height: 25px;
        font-size: 20px;
        text-decoration: none;
    }
</style>