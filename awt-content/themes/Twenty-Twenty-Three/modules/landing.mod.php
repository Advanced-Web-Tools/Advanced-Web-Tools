<?php $theme->loadCSS('/css/home.css'); ?>
<section class="landing">
        <div class="container">
            <div class="heading-container">
                <h1><?php echo $data['title']['_text']; ?></h1>
            </div>
            <div class="description-container">
                <p><?php echo $data['paragraph']['_text']; ?></p>
            </div>
            <div class="button-container">
                <a href="<?php echo $data['button']['link']; ?>">
                    <button class="landing-button"><?php echo $data['button']['title']; ?></button>
                </a>
            </div>
        </div>
</section>