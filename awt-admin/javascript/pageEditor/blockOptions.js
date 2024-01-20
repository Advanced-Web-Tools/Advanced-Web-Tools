class BlockOption {
    option
    $block
    constructor(condition, callback, optionalCallback = null) {
        this.option = {
            condition: condition,
            callback: callback,
            optionalCallback: optionalCallback
        }
    }

    loadOption($block, defaultStyle) {
        if (this.option.condition($block)) {
            this.option.callback($block, defaultStyle);
            if(this.option.optionalCallback) this.option.optionalCallback($block);
        }
    }

}