<?php

defined('DASHBOARD') or die("You should not do that..");
defined('ALL_CONFIG_LOADED') or die("An error has occured");

use admin\authentication;

$check = new authentication;

if (!$check->checkAuthentication()) {
    header("Location: ./login.php");
    exit();
}

?>
<link rel="stylesheet" href="./css/store.css">
<section class="store">
    <div class="search-container">
        <select name="type" class="select" id="search-type">
            <option value="*">Search for...</option>
            <option value="plugin">Plugins</option>
            <option value="theme">Themes</option>
        </select>
        <input type="text" class="input" placeholder="Search...">
        <button class="button"
            onclick="searchStore('.search-container .input', '.search-container .select', '.store-front');"><i
                class="fa-solid fa-magnifying-glass"></i></button>
    </div>

    <div class="store-front">

    </div>
</section>
<script src="https://unpkg.com/color.js@1.2.0/dist/color.js"></script>
<script src="./javascript/store/search.js"></script>
<script src="./javascript/store/installation.js"></script>
<script src="./javascript/store/store.js"></script>
<script>
    $(document).ready(function() {
        var QueryString = (new URL(location.href)).searchParams.get('viewStore');
        if(QueryString == null) {
            loadPage(".store-front");
        } else {
            loadStore(".store-front", QueryString);
        }
    });
</script>