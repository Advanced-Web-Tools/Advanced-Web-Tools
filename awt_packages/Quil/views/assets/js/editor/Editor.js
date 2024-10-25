export class Editor {
    openBlockSelector(container) {
        if (container.hasClass("active")) {
            container.toggleClass("active");
            setTimeout(function (e) {
                container.toggleClass("hidden");
            }, 201);
        } else {
            container.toggleClass("hidden");
            setTimeout(function (e) {
                container.toggleClass("active");
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
}

