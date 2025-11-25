import {AWTRespondRequest} from "../../../../../AWTRespond/js/AWTRespond.js";
import {Notify} from "../../../../../Dashboard/js/ui/Notify.js";

export class Editor {

    openBlockSelector(container) {
        const editor = $(".main .editor");
        const button = $("#add_block");

        const isOpening = container.hasClass("hidden");
        
        button.toggleClass("active", isOpening);
        editor.toggleClass("shrink", isOpening);

        if (isOpening) {
            container.removeClass("hidden");
            // requestAnimationFrame ensures transition
            requestAnimationFrame(() => {
                container.addClass("active");
            });
        } else {
            container.removeClass("active");
            // Wait for transition
            container.one('transitionend', () => {
                container.addClass("hidden");
            });
        }
    }

    mobileView(editorPage) {
        editorPage.toggleClass("mobile");
        $("#mobile").toggleClass("active");
    }

    sanitizeContent(html, inner = false) {
        const $content = $(`<div>${html}</div>`);

        $content.find("[save='false']").remove();
        $content.find("[selected='selected']").removeAttr("selected");
        $content.find("[contenteditable='true']").removeAttr("contenteditable");
        $content.find(".contextWrapper").remove();
        $content.find("hr.insert").remove();
        
        const firstChild = $content.children().first();
        
        firstChild.removeClass("mobile");
        firstChild.removeAttr("selected");

        if (inner) {
            return firstChild.html();
        }

        return firstChild[0].outerHTML;
    }

    async savePage(id) {
        const content = $(".main .editor .page")[0].outerHTML;
        const page = this.sanitizeContent(content);
        const api = new AWTRespondRequest('', { 'Content-Type': 'application/x-www-form-urlencoded' });
        
        try {
            const response = await api.post("/quil/save/" + id, {
                id: id,
                content: page
            });

            if (response.code === 200) {
                new Notify("Page saved successfully.", "positive", 5000, response.content).create();
            } else if (response.code === 300) {
                new Notify("Page was not saved.", "warning", 5000, response.content).create();
            } else {
                new Notify("Fatal error has occurred.", "negative", 5000, response.content).create();
            }
        } catch (error) {
            console.error("Save failed:", error);
            new Notify("Fatal error has occurred.", "negative", 5000, "Check console for details.").create();
        }
    }
}