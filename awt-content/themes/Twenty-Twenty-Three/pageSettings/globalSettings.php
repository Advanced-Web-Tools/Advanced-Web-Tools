<div class="colors">
    <div class="options-header">
        <h4>Colors</h4>
    </div>
    <div class="options hidden">
        <label for="color1">Primary Color</label>
        <input value="<?php echo $colors["primary-color"]; ?>" name="color[primary-color]" type="text" id="color1" onchange='colorEditor(this, "primary-color", "#inline-styles");'>
        <label for="color1">Primary Color Variant</label>
        <input value="<?php echo $colors["primary-color-variant"]; ?>" name=" color[primary-color-vraiant]" id="color2" onchange='colorEditor(this, "primary-color-variant", "#inline-styles");'>
        <label for="color1">Secondary Color</label>
        <input value="<?php echo $colors["secondary-color"]; ?>" name=" color[secondary-color]" id="color3" onchange='colorEditor(this, "secondary-color", "#inline-styles");'>
        <label for="color1">Secondary Color Variant</label>
        <input value="<?php echo $colors["secondary-color-variant"]; ?>" name=" color[secondary-color-vraint]" id="color4" onchange='colorEditor(this, "secondary-color-variant", "#inline-styles");'>
        <label for="color1">Text Color</label>
        <input value="<?php echo $colors["text-color"]; ?>" name=" color[text-color]" id="color5" onchange='colorEditor(this, "text-color", "#inline-styles");'>
        <label for="color1">Text Color Variant</label>
        <input value="<?php echo $colors["text-color-variant"]; ?>" name=" color[text-color-variant]" id="color5" onchange='colorEditor(this, "text-color-variant", "#inline-styles");'>
        <label for="color1">Button Color</label>
        <input value="<?php echo $colors["primary-button"]; ?>" name=" color[primary-button]" id="color5" onchange='colorEditor(this, "primary-button", "#inline-styles");'>
    </div>
</div>