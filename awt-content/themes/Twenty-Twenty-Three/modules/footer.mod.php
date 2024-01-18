<?php echo $theme->loadCSS('/css/footer.css'); ?>

<section class="footer">
    <div class="contact">
        <a href="mailto:<?php echo CONTACT_EMAIL; ?>"><?php echo CONTACT_EMAIL; ?></a>
        <div class="wrapper">
                <a href="#"><i class="fa-brands fa-twitter"></i></a>

                <a href="#"><i class="fa-brands fa-instagram"></i></a>

                <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>

        </div>
    </div>
    <div class="links">
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