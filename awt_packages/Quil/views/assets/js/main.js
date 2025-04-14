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

function prettyFormatHtmlString(htmlString) {
    const parser = new DOMParser();
    const doc = parser.parseFromString(htmlString, "text/html");
    const body = doc.body;

    const inlineTags = new Set([
        'a', 'abbr', 'acronym', 'b', 'bdo', 'big', 'br', 'button', 'cite',
        'code', 'dfn', 'em', 'i', 'img', 'input', 'kbd', 'label', 'map',
        'object', 'output', 'q', 'samp', 'script', 'select', 'small',
        'span', 'strong', 'sub', 'sup', 'textarea', 'time', 'tt', 'var'
    ]);

    function isInline(node) {
        return node.nodeType === 1 && inlineTags.has(node.tagName.toLowerCase());
    }

    function walk(node, indent = 0) {
        const spacer = "  ".repeat(indent);
        let html = "";

        if (node.nodeType === Node.TEXT_NODE) {
            const text = node.textContent;
            if (!text.trim()) return "";
            return spacer + text.trim();
        }

        if (node.nodeType === Node.COMMENT_NODE) {
            return `${spacer}<!-- ${node.nodeValue.trim()} -->`;
        }

        if (node.nodeType === Node.ELEMENT_NODE) {
            const tagName = node.tagName.toLowerCase();
            const isScriptOrStyle = tagName === "script" || tagName === "style";

            // Attributes
            const attrs = [...node.attributes].map(attr => {
                if (attr.name === "style") {
                    // Preserve inline styles exactly
                    return `${attr.name}="${attr.value}"`;
                }
                return `${attr.name}="${attr.value}"`;
            }).join(" ");

            const openTag = attrs ? `<${tagName} ${attrs}>` : `<${tagName}>`;
            const closeTag = `</${tagName}>`;

            // Preserve inner content for <style> and <script>
            if (isScriptOrStyle) {
                const raw = node.innerHTML;
                return `${spacer}${openTag}${raw}${closeTag}`;
            }

            const children = [...node.childNodes];
            const isInlineElement = isInline(node);
            const allInline = children.every(isInline);

            if (isInlineElement || allInline) {
                const inner = children.map(c => walk(c, 0)).join("");
                return `${spacer}${openTag}${inner}${closeTag}`;
            }

            // Default block formatting
            html += `${spacer}${openTag}\n`;
            children.forEach(child => {
                const childHtml = walk(child, indent + 1);
                if (childHtml) html += childHtml + "\n";
            });
            html += `${spacer}${closeTag}`;
        }

        return html;
    }

    return [...body.childNodes].map(child => walk(child, 0)).join("\n").trim();
}




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

    const target = document.querySelector(".editor .page");

    scene.history.addToHistory(new Editor().sanitizeContent(editorPage[0].outerHTML, true));

    const codemirror = CodeMirror.fromTextArea(document.querySelector(".editor .code"), {
        mode: "htmlmixed",
        lineNumbers: true,
        styleActiveLine: true,
        theme: "dracula",
        lineWrapping: true,
        tabSize: 2,
    });

    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            const latest = new Editor().sanitizeContent(editorPage[0].outerHTML, true);
            if (scene.history.history[scene.history.history.length - 1] !== latest) {
                scene.history.addToHistory(latest);
                codemirror.setValue(latest);
            }
        });
    });

    const config = {
        childList: true,
        attributes: false,
        subtree: true,
        characterData: true
    };


    observer.observe(target, config);

    function pauseObserver() {
        observer.disconnect();
    }

    function resumeObserver() {
        const updatedTarget = document.querySelector(".editor .page");
        observer.observe(updatedTarget, config);
    }

    $("#undo").click((e) => {
        const newHTML = scene.history.retrieveFromHistory(-1);
        if (newHTML === null) return;

        pauseObserver();
        editorPage.html(newHTML);
        scene.reattachEventsToScene();
        resumeObserver();
    });

    $("#redo").click((e) => {
        const newHTML = scene.history.retrieveFromHistory(1);
        if (newHTML === null) return;

        pauseObserver();
        editorPage.html(newHTML);
        scene.reattachEventsToScene();
        resumeObserver();
    });

    addEventListener("keypress", (e) => {
        if (e.ctrlKey && e.code === 'KeyZ') {
            e.preventDefault();
            const newHTML = scene.history.retrieveFromHistory(-1);
            if (newHTML === null) return;

            pauseObserver();
            editorPage.html(newHTML);
            scene.reattachEventsToScene();
            resumeObserver();
        }


        if (e.ctrlKey && e.code === 'KeyY') {
            e.preventDefault();
            const newHTML = scene.history.retrieveFromHistory(1);
            if (newHTML === null) return;

            pauseObserver();
            editorPage.html(newHTML);
            scene.reattachEventsToScene();
            resumeObserver();
        }
    });

    codemirror.getWrapperElement().classList.add("hidden");

    $("#toggle").change((e) => {
        const visualView = $(".editor .page");

        if (codemirror.getWrapperElement().classList.contains("hidden")) {
            const raw = new Editor().sanitizeContent(visualView[0].outerHTML, true);

            const formatted = html_beautify(raw);

            codemirror.setValue(formatted);
            codemirror.setSize("100%", "100%");
            codemirror.getWrapperElement().classList.remove("hidden");
        } else {
            visualView.html(codemirror.getValue());
            codemirror.getWrapperElement().classList.add("hidden");
        }

        visualView.toggleClass("hidden");
    });



    isInit = true;
}

$(document).ready((e) => {
    init(e);
});




