import {Options} from "./editor/options/Options.js";
import {Blocks} from "./editor/blocks/Blocks.js";
import {Scene} from "./editor/Scene.js";
import {Editor} from "./editor/Editor.js";
import {PopulateHelper} from "../../../../Dashboard/js/ui/Helper.js";
import {EditorOptions} from "./editor/editorOptions/EditorOptions.js";

const editor = $(".editor");
const editorPage = $(".editor .page");
const leftAside = $(".main .left");

export let options = new Options();
export let blocks = new Blocks();
const scene = new Scene(editorPage, blocks, options);
let isInit = false;

function init(e) {
    if (isInit)
        return;

    blocks.drawList(".main .left .content");

    scene.dataSources.fetchDataSources();

    $("#add_block").click((e) => {
        new Editor().openBlockSelector(leftAside);
    });

    $(".main .left .action").click((e) => {
        new Editor().openBlockSelector(leftAside);
    });

    $("button#save").click((e) => {
        const urlParams = new URLSearchParams(window.location.search)
        const paramValue = urlParams.get('id');
        new Editor().savePage(paramValue);
    });

    $("#mobile").click((e) => {
        new Editor().mobileView(editorPage);
    });


    $("#editor_options").click((e) => {
        PopulateHelper("", new EditorOptions().draw());
    });

    $("#manage").click((e) => {
        PopulateHelper("", scene.dataSources.drawHelper());
    });

    new EditorOptions().apply();

    scene.reattachEventsToScene();

    isInit = true;
}

$(document).ready((e) => {
    init(e);
});




