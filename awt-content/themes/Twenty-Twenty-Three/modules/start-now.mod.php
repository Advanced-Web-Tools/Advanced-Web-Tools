<?php $theme->loadCSS('/css/startNow.css'); ?>

<section class="start-now">
    <h3><?php echo $data['title']['_text']; ?></h3>
    <div class="wrapper">
        <a href="<?php echo $data['button']['link']; ?>"><button><?php echo $data['button']['title']; ?></button></a>
    </div>
</section>