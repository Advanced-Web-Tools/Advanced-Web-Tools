import {Options} from "./editor/options/Options";
import {Blocks} from "./editor/blocks/Blocks";
import {Scene} from "./editor/Scene";
import {Editor} from "./editor/Editor";

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

    $("#add_block").click((e) => {
        new Editor().openBlockSelector(leftAside);
    });

    $(".main .left .action").click((e) => {
        new Editor().openBlockSelector(leftAside);
    });

    $("#mobile").click((e) => {
        new Editor().mobileView(editorPage);
    });

    scene.reattachEventsToScene();

    isInit = true;
}

$(document).ready((e) => {
    init(e);
});




