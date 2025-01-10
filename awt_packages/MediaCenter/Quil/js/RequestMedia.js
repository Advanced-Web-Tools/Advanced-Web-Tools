import {AWTRespondRequest} from "../../../AWTRespond/js/AWTRespond.js";

let api = new AWTRespondRequest('');
export function RequestMedia(callback)
{
    api.sendRequest("/api/mediacenter/getMedia/", "GET").then(data => callback(data)).catch(error => console.error(error));
}