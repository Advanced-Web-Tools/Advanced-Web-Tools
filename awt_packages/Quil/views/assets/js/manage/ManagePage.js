import {AWTRespondRequest} from "../../../../../AWTRespond/js/AWTRespond.js";

export class ManagePage {
    constructor(id) {
        this.id = id;
        this.info = {};
    }


    async getInfo()
    {
        const api = new AWTRespondRequest("");

        await api.get(`/quil/info/${this.id}`).then((result) => {
            this.info = result;
        });

        return this.info;
    }

}