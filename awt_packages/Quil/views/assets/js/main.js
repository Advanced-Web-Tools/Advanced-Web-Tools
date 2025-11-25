import {Options} from "./editor/options/Options.js";
import {Blocks} from "./editor/blocks/Blocks.js";
import {Scene} from "./editor/Scene.js";
import {Editor} from "./editor/Editor.js";
import {PopulateHelper} from "../../../../Dashboard/js/ui/Helper.js";
import {EditorOptions} from "./editor/editorOptions/EditorOptions.js";

const editorPage = $(".editor .page");
const leftAside = $(".main .left");

export let options = new Options();
export let blocks = new Blocks();
const editor = new Editor(); // Jedna instanca Editor klase
const editorOptions = new EditorOptions(editor); // Prosleđujemo zavisnost
const scene = new Scene(editorPage, blocks, options, editor); // Prosleđujemo zavisnost

let isInit = false;

function initCodeMirror() {
    const codemirror = CodeMirror.fromTextArea(document.querySelector(".editor .code"), {
        mode: "htmlmixed",
        lineNumbers: true,
        styleActiveLine: true,
        theme: "dracula",
        lineWrapping: true,
        tabSize: 2,
    });
    codemirror.getWrapperElement().classList.add("hidden");

    $("#toggle").change((e) => {
        const visualView = $(".editor .page");
        const codeView = $(codemirror.getWrapperElement());

        if (codeView.hasClass("hidden")) {
            const raw = editor.sanitizeContent(visualView[0].outerHTML, true);
            const formatted = html_beautify(raw);
            codemirror.setValue(formatted);
            codemirror.setSize("100%", "100%");
        } else {
            visualView.html(codemirror.getValue());
            scene.markEmptyBlocks(); // Osveži prazne blokove nakon promene iz koda
        }
        
        codeView.toggleClass("hidden");
        visualView.toggleClass("hidden");
    });
    
    return codemirror;
}

function initMutationObserver(codemirror) {
    const target = document.querySelector(".editor .page");
    
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            const latest = editor.sanitizeContent(editorPage[0].outerHTML, true);
            const lastHistoryState = scene.history.history[scene.history.pointer];

            if (lastHistoryState !== latest) {
                scene.history.addToHistory(latest);
                codemirror.setValue(latest);
            }
        });
    });
    
    observer.observe(target, { childList: true, subtree: true, characterData: true });
    
    return observer;
}

function initEventListeners(observer) {
    const urlParams = new URLSearchParams(window.location.search);
    const pageId = urlParams.get('id');

    const pauseObserver = () => observer.disconnect();
    const resumeObserver = () => observer.observe(document.querySelector(".editor .page"), { childList: true, subtree: true, characterData: true });
    
    const handleUndoRedo = (direction) => {
        const newHTML = scene.history.retrieveFromHistory(direction);
        if (newHTML === null) return;
        
        pauseObserver();
        editorPage.html(newHTML);
        scene.markEmptyBlocks(); // Osveži prazne blokove
        resumeObserver();
    };

    $("#add_block, .main .left .action").click(() => editor.openBlockSelector(leftAside));
    $("#save").click(() => editor.savePage(pageId));
    $("#mobile").click(() => editor.mobileView(editorPage));
    $("#editor_options").click(() => PopulateHelper("", editorOptions.draw()));
    $("#manage").click(() => PopulateHelper("", scene.dataSources.drawHelper()));
    $("#undo").click(() => handleUndoRedo(-1));
    $("#redo").click(() => handleUndoRedo(1));

    $(document).on("keydown", (e) => {
        if (e.ctrlKey) {
            if (e.code === 'KeyZ') {
                e.preventDefault();
                handleUndoRedo(-1);
            } else if (e.code === 'KeyY') {
                e.preventDefault();
                handleUndoRedo(1);
            }
        }
    });
}

function init() {
    if (isInit) return;
    isInit = true;

    blocks.drawList(".main .left .content");
    scene.dataSources.fetchDataSources();
    
    const codemirror = initCodeMirror();
    const observer = initMutationObserver(codemirror);
    initEventListeners(observer);
    
    editorOptions.apply();
    
    scene.history.addToHistory(editor.sanitizeContent(editorPage[0].outerHTML, true));
}

$(document).ready(init);