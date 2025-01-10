export class Options {
    constructor() {
        this.options = [];  // Changed to an array if you want to use `forEach` later
        this.current_block = $(".editor .page");
        this.addedCats = [];
    }

    setCurrentBlock(block) {
        this.current_block = block;
    }

    addOption(option) {
        this.options.push(option);  // Use push since `this.options` is now an array
    }

    getOptions() {
        return this.options;
    }

    startRender() {
        this.addedCats = [];
        $(".main .right .content").html("")

        const category_container = document.createElement("div");
        category_container.classList.add("category-container");
        document.querySelector(".main .right .content").appendChild(category_container);

        this.options.forEach((option, key) => {

            option.setCurrentBlock(this.current_block);

            if(!option.callableCondition())
                return;

            const category_wrapper = document.createElement("div");
            const category_selector = document.createElement("div");
            const category_selector_text = document.createElement("p");

            category_wrapper.classList.add("option-category");

            category_selector.classList.add("category-selector");

            category_wrapper.id = option.category;
            category_selector.setAttribute("data-toggle", option.category);
            category_selector_text.innerText = option.category;

            if(!this.addedCats.includes(option.category)) {
                category_selector.appendChild(category_selector_text);
                this.addedCats.push(option.category);
            }

            document.querySelector(".main .right .content .category-container").appendChild(category_selector);
            document.querySelector(".main .right .content").appendChild(category_wrapper);

            if (Array.isArray(option.drawable)) {
                option.drawable.forEach((drawable, key) => {
                    option.render(key, option.category);
                });
            }
        });

        $(".category-selector[data-toggle='Default']").toggleClass("active");
        $(`#Default.option-category`).toggleClass("active");

        this.addedCats.forEach((key, index) => {
            const cat = $(".category-selector[data-toggle='" + key + "']");
            cat.on("click", (e) => {
                $(".category-selector.active").toggleClass("active");
                $(".option-category.active").toggleClass("active");
                $(`#${key}.option-category`).toggleClass("active");
                cat.toggleClass("active");
                this.lastCat = key;
            });
        });

    }
}
