<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $paging->pages['Home']['description']; ?>">
    <title>
        <?php echo WEB_NAME . " | Home"; ?>
    </title>
</head>

<body>
    <?php $theme->loadModule("Menu"); ?>
    <?php $theme->loadCSS('/css/home.css'); ?>
    <?php $theme->loadCSS('/css/startNow.css'); ?>
    <?php $theme->loadCSS('/css/presentation.css'); ?>
    <?php $theme->loadCSS('/css/features.css'); ?>
    <section class="pageSection">
        <section class="landing">
            <div class="container">
                <div class="heading-container block">
                    <h1>
                        Launch your website with a stunning Twenty-Twenty-Three theme.
                    </h1>
                </div>
                <div class="description-container block">
                    <p>
                        Our theme is designed to make your website stand out and impress your audience. With easy
                        customization options, you can have a professional-looking website in no time. Say goodbye to
                        boring
                        and hello to Twent-Twenty-Three.
                    </p>
                </div>
                <div class="button-container block">
                    <a href="#">
                        <button class="landing-button">
                            Get Started
                        </button>
                    </a>
                </div>
            </div>
        </section>
        <section class="presentation block">
            <div class="container">
                <img class="block" src="<?php echo $theme->getAssetLink("/assets/images/product-shot-p-800.webp"); ?>"
                    alt="Product shot">
                <h2>Sleek and modern theme for your next project</h2>
                <p> Upgrade your website with our cutting-edge theme designed to make a lasting impression. Our
                    templates are easy to customize and perfect for businesses of all sizes. Say goodbye to outdated
                    designs and hello to a fresh new look with Twenty-Twenty-Three. </p>
                </p>
            </div>
        </section>
        <section class="features block">
            <div class="container block">
                <div class="wrapper">
                    <h3>
                        Create a stunning website with ease.
                    </h3>
                    <p>
                        Don't let your lack of coding skills hold you back from creating a beautiful website. With
                        Twent-Twenty-Three, you can build an amazing website without any hassle. Our theme is
                        user-friendly
                        and customizable to help bring your vision to life. Start building your dream website today!
                    </p>
                </div>
                <span>
                    <img src="<?php echo $theme->getAssetLink("/assets/images/marginalia-coming-soon.webp"); ?>"
                        alt="Create a stunning website with ease. ">
                </span>
            </div>
            <div class="container block">
                <div class="wrapper">
                    <h3>
                        Launch your website in minutes, not hours.
                    </h3>
                    <p>
                        Don't waste time on complicated website setups. With Twent-Twenty-Three's easy-to-use and
                        customizable theme, you can launch your website in just a few clicks. Get your online presence
                        up
                        and running quickly so you can focus on growing your business.
                    </p>
                </div>
                <span>
                    <img src="<?php echo $theme->getAssetLink("/assets/images/marginalia-order-complete.webp"); ?>"
                        alt="Launch your website in minutes, not hours.">
                </span>
            </div>
            <div class="container block">
                <div class="wrapper">
                    <h3>
                        Effortlessly create stunning landing pages.
                    </h3>
                    <p>
                        With Twent-Twenty-Three, creating a beautiful landing page is as easy as dropping blocks into
                        the
                        page editor. Say goodbye to hours spent coding and designing—focus on your message and let our
                        theme
                        do the rest. Create stunning pages in no time with Twent-Twenty-Three.
                    </p>
                </div>
                <span>
                    <img src="<?php echo $theme->getAssetLink("/assets/images/marginalia-order-complete.webp"); ?>"
                        alt="Effortlessly create stunning landing pages.">
                </span>
            </div>
        </section>


        <section class="start-now">
            <h3>
                Make your website stand out with the Twent-Twenty-Three theme.
            </h3>
            <div class="wrapper">
                <a href="./awt-admin/?page=Customize"><button>
                        Start Now
                    </button></a>
            </div>
        </section>
    </section>

    <?php $theme->loadModule("Footer"); ?>
</body>

</html>