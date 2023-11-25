<?php $theme->loadCSS('/css/presentation.css'); ?>
<section class="presentation">
    <div class="container">
        
        <img src="<?php echo $theme->getAssetLink($data['image']['file']); ?>" alt="<?php echo $data['image']['title']; ?>" class="shadow">
        <h2><?php echo $data['title']['_text']; ?></h2>
        <p><?php echo $data['paragraph']['_text']; ?></p>
    </div>
</section>