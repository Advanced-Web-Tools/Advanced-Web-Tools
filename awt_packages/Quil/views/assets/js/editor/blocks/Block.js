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
        if(this.name === undefined)
            return "Unnamed block";
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