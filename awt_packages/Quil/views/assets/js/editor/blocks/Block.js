export class Block {
    constructor() {
        this.name = "name";
        this.category = "category";
        this.body = null;
        this.fa_icon_class = "";
    }

    setName(name) {
        this.name = name;
    }

    setFaIcon(className) {
        this.fa_icon_class = className;
    }

    setCategory(category) {
        this.category = category;
    }

    getName() {
        return this.name;
    }

    getCategory() {
        return this.category;
    }

    addBody(body) {
        this.body = $(body);
    }

    getBody() {
        this.body.attr("block-name", this.name);
        return this.body.clone();
    }

}