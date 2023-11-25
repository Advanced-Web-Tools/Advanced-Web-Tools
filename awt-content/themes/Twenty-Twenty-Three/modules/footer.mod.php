<?php $theme->loadCSS('/css/footer.css'); ?>

<section class="footer">
    <div class="contact">
        <a href="mailto:<?php echo CONTACT_EMAIL; ?>"><?php echo CONTACT_EMAIL; ?></a>
        <div class="wrapper">
            <?php if ($data['twitter']['status'] == "Enabled") : ?>
                <a href="<?php echo $data['twitter']['link']; ?>"><i class="fa-brands fa-twitter"></i></a>
            <?php endif;
            if ($data['instagram']['status'] == "Enabled") : ?>
                <a href="<?php echo $data['instagram']['link']; ?>"><i class="fa-brands fa-instagram"></i></a>
            <?php endif;
            if ($data['linkedin']['status'] == "Enabled") : ?>
                <a href="<?php echo $data['linkedin']['link']; ?>"><i class="fa-brands fa-linkedin-in"></i></a>
            <?php endif; ?>
        </div>
    </div>
    <div class="links">
        <?php ?>
        <ul>
            <?php foreach (MENU_ITEMS as $key => $value) : ?>
                <li><?php echo $value; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="copyright">
        <hr>
        <p>Copyrigth &copy; <?php echo WEB_NAME; ?></p>
    </div>
</section>