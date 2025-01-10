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

    changeEditor() {

    }

    mobileView(editorPage) {
        editorPage.toggleClass("mobile");

        $("#mobile").toggleClass("active");
    }

    sanitizeContent(html) {
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

        $content.removeClass("mobile");

        $content.removeAttr("selected");

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
                new Notify("Page saved successfully.", "positive", 10000, response.content).create();
            } else if (response.code === 300) {
                new Notify("Page was not saved.", "warning", 10000, response.content).create();
            } else {
                new Notify("Fatal error has occurred.", "negative", 10000, response.content).create();
            }
        });

    }
}

