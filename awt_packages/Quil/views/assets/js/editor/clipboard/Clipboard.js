export class Clipboard {
    constructor() {
        this.content = "";
        this.style = "";
    }


    setContent(content) {
        this.content = content;
    }

    getContent() {
        return this.content;
    }

}