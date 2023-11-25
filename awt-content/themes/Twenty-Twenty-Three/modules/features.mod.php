<?php $theme->loadCSS('/css/features.css'); ?>

<section class="features">
    <div class="container">
        <div class="wrapper">
            <h3><?php echo $data['feature_1']['title']; ?></h3>
            <p><?php echo $data['feature_1']['paragraph']; ?></p>
        </div>
        <span>
            <img src="<?php echo $theme->getAssetLink($data['feature_1']['file']); ?>" alt="<?php echo $data['feature_1']['title']; ?>">
        </span>
    </div>
    <div class="container">
        <div class="wrapper">
            <h3><?php echo $data['feature_2']['title']; ?></h3>
            <p><?php echo $data['feature_2']['paragraph']; ?></p>
        </div>
        <span>
            <img src="<?php echo $theme->getAssetLink($data['feature_2']['file']); ?>" alt="<?php echo $data['feature_2']['title']; ?>">
        </span>
    </div>
    <div class="container">
        <div class="wrapper">
            <h3><?php echo $data['feature_3']['title']; ?></h3>
            <p><?php echo $data['feature_3']['paragraph']; ?></p>
        </div>
        <span>
            <img src="<?php echo $theme->getAssetLink($data['feature_3']['file']); ?>" alt="<?php echo $data['feature_3']['title']; ?>">
        </span>
    </div>
</section>