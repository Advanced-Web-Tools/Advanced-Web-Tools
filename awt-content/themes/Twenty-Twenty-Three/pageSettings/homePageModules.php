<div class="modules">
    <div class="options-header">
        <h4>Module Order</h4>
    </div>
    <div class="options hidden">
    </div>
</div>
<div class="modules-settings">
    <div class="options-header">
        <h4>Module Settings</h4>
    </div>
    <div class="options hidden">
        <h5 class="landing-page-opts">Landing Page</h5>
        <div class="landing-page-opt hidden">
            <label for="landing-title">Landing Page Title</label>
            <input type="text" id="landing-title" onkeyup="textEditor(this, '.heading-container h1')">
            <label for="landing-description">Landing Page Description</label>
            <input type="text" id="landing-description" onkeyup="textEditor(this, '.description-container p')">
            <label for="landing-button-text">Landing Page Button Text</label>
            <input type="text" id="landing-button-Text" onkeyup="textEditor(this, '.button-container button')">
            <label for="landing-button-link">Landing Page Button Link</label>
            <input type="text" id="landing-button-link" onkeyup="linkEditor(this, '.button-container a')">
        </div>
    </div>
</div>
<script>
     openSubmenu(".modules-settings .options .landing-page-opts", ".modules-settings .options .landing-page-opt ", "hidden");
     loadText('.heading-container h1', '#landing-title');
     loadText('.description-container p', '#landing-description');
     loadText('.heading-container h1', '#landing-title');
</script>