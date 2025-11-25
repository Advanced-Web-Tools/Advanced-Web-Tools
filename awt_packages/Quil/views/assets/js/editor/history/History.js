export class History {
    constructor() {
        this.history = [];
        this.pointer = -1;
    }

    /**
     * Adds new state to history
     * @param {string} content
     */
    addToHistory(content) {
        if (this.pointer < this.history.length - 1) {
            this.history.splice(this.pointer + 1);
        }
        this.history.push(content);
        this.pointer++;
    }

    /**
     * Returns state from history
     * @param {number} direction -1 for undo, 1 for redo
     * @returns {string|null}
     */
    retrieveFromHistory(direction) {
        const newPointer = this.pointer + direction;

        if (newPointer >= 0 && newPointer < this.history.length) {
            this.pointer = newPointer;
            return this.history[this.pointer];
        }

        return null;
    }
}