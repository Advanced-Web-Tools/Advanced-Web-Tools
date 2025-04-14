import {AWTRespondRequest} from "../../../../../AWTRespond/js/AWTRespond.js";
import {Notify} from "../../../../../Dashboard/js/ui/Notify.js";

export class Editor {

    openBlockSelector(container) {
        if (container.hasClass("active")) {
            container.toggleClass("active");
            setTimeout(function (e) {
                container.toggleClass("hidden");
                $(".main .editor").toggleClass("shrink");
            }, 201);


        } else {
            container.toggleClass("hidden");
            setTimeout(function (e) {
                container.toggleClass("active");
                $(".main .editor").toggleClass("shrink");
            }, 1);
        }

        const button = $("#add_block");

        button.toggleClass("active");
    }

    mobileView(editorPage) {
        editorPage.toggleClass("mobile");

        $("#mobile").toggleClass("active");
    }

    sanitizeContent(html, inner = false) {
        const $content = $(html);

        $content.find("[save='false']").each(function () {
            $(this).remove();
        });

        $content.find("[selected='selected']").each(function () {
            $(this).removeAttr("selected");
        });

        $content.find("[contenteditable='true']").each(function () {
            $(this).removeAttr("contenteditable");
        });

        $content.find(".contextWrapper").each(() => {
            $(this).remove();
        });

        $content.find("hr.insert").each(() => {
            $(this).remove();
        });

        $content.removeClass("mobile");

        $content.removeAttr("selected");

        if(inner)
            return $content.html();

        return $content[0].outerHTML;
    }

    async savePage(id) {
        const content = $(".main .editor .page")[0].outerHTML;
        const page = this.sanitizeContent(content);
        const api = new AWTRespondRequest('', { 'Content-Type': 'application/x-www-form-urlencoded' });
        await api.post("/quil/save/" + id, {
            id: id,
            content: page
        }).then(response => {
            if(response.code === 200) {
                new Notify("Page saved successfully.", "positive", 5000, response.content).create();
            } else if (response.code === 300) {
                new Notify("Page was not saved.", "warning", 5000, response.content).create();
            } else {
                new Notify("Fatal error has occurred.", "negative", 5000, response.content).create();
            }
        });

    }
}

