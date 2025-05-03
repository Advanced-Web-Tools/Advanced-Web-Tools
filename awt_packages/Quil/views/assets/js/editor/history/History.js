export class History {
    constructor() {
        this.history = [];
        this.pointer = 0;
    }

    addToHistory(content)
    {
        if (this.pointer < this.history.length) {
            this.history.splice(this.pointer, this.history.length - this.pointer);
        }

        this.history.push(content);
        this.pointer++;

        console.log(this.history)
    }

    retrieveFromHistory(direction)
    {
        if(direction === -1 && this.pointer !== 0) {
            this.pointer--;
            return this.history[this.pointer];
        }

        if(direction === 1 && this.pointer < this.history.length) {
            this.pointer++;
            return this.history[this.pointer];
        }

        return null;
    }

}