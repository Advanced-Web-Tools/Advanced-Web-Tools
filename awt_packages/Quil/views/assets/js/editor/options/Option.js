export class Option {
    constructor() {
        this.drawable = [];
        this.callableCondition = () => true;
        this.current_block = null;
        this.attachable = new Map();
        this.category = "Default";
    }

    setCategory(category)
    {
        this.category = category;
    }

    setCurrentBlock(current_block)
    {
        this.current_block = current_block;
    }

    addDrawable(key, element) {
        this.drawable[key] = element;
    }

    setCallableCondition(conditionFunc) {
        this.callableCondition = conditionFunc;
    }

    attachFunction(key, callback) {
        if (this.drawable[key]) {
            this.attachable.set(key, callback);
        }
    }

    execute(key) {
        if (this.attachable.has(key) && typeof this.attachable.get(key) === 'function') {
            this.attachable.get(key)(this.current_block);
        }
    }

    render(key, category = "default") {
        if (this.callableCondition(this.current_block)) {
            if (this.drawable[key]) {
                const targetContainer = document.querySelector(".main .right .content #" + category);
                if (targetContainer) {
                    targetContainer.appendChild(this.drawable[key]);
                    this.execute(key);
                }
            }
        }
    }

}