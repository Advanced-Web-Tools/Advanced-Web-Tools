import {ManagePage} from "./ManagePage.js";
import {PopulateHelper} from "../../../../../Dashboard/js/ui/Helper.js";
import {AWTRespondRequest} from "../../../../../AWTRespond/js/AWTRespond.js";
import {Notify} from "../../../../../Dashboard/js/ui/Notify.js";
import {CreateRouteUI} from "./routes/CreateRouteUI.js";

function createInput(info)
{
    const container = document.createElement("div");
    container.classList.add("manager");


    const title = document.createElement("h2");
    title.textContent = info.page.name;
    title.style.width = "100%";
    title.style.textAlign = "center";

    container.appendChild(title);

    const nameLabel = document.createElement("label");
    nameLabel.textContent = "Page name:";
    nameLabel.setAttribute("for", "name");

    container.appendChild(nameLabel);

    const input = document.createElement("input");
    input.id = "name";
    input.classList.add("input_name");
    input.classList.add("inp_primary");
    input.classList.add("md");
    input.type = "text";
    input.value = info.page.name;

    container.appendChild(input);

    const descLabel = document.createElement("label");
    descLabel.textContent = "Description:";
    descLabel.setAttribute("for", "desc");

    container.appendChild(descLabel);

    const textArea = document.createElement("textarea");
    textArea.id = "desc";
    textArea.classList.add("input_description");
    textArea.classList.add("inp_primary");
    textArea.classList.add("md");
    textArea.value = info.page.description;
    textArea.style.resize = "none";
    textArea.style.height = "300px";


    container.appendChild(textArea);

    const route = document.createElement("a");
    route.classList.add("route");
    route.target = "_blank";
    route.href = info.route.path;
    route.textContent = info.page.name;

    const author = document.createElement("p");
    author.classList.add("author_name");
    author.textContent = info.author.name;


    const saveButton = document.createElement("button");
    saveButton.classList.add("save_edit");
    saveButton.classList.add("btn_secondary");
    saveButton.innerHTML = "Save changes <i class='fas fa-floppy-disk'></i> ";
    saveButton.setAttribute("data-id", info.page.id);

    const selLabel = document.createElement("label");
    selLabel.textContent = "Selected route:";
    selLabel.setAttribute("for", "select_route");


    container.appendChild(selLabel);

    const selectRoute = document.createElement("select");
    selectRoute.classList.add("md");
    selectRoute.classList.add("select_primary");
    selectRoute.id = "select_route";
    selectRoute.name = "route_id";

    const val = document.createElement("option");
    val.textContent = "Route not selected.";
    val.value = null;
    selectRoute.appendChild(val);

    Object.values(info.routes[0]).forEach((rt, key) => {
        const val = document.createElement("option");
        val.textContent = `${rt.id}: ${rt.route}`;
        val.value = rt.id;

        console.log(rt)

        selectRoute.appendChild(val);
    });


    selectRoute.value = info.route.id;

    container.appendChild(selectRoute);

    console.log(info)

    saveButton.addEventListener("click", async (e) => {
        const target = e.currentTarget;
        const id = target.dataset.id;

        const api = new AWTRespondRequest('', { 'Content-Type': 'application/x-www-form-urlencoded' });
        try {
            const response = await api.post("/quil/save/" + id, {
                id: id,
                name: input.value,
                description: textArea.value,
                route_id: selectRoute.value,
            });

            if (response.code === 200) {
                new Notify("Page saved successfully.", "positive", 10000, response.content).create();
            } else if (response.code === 300) {
                new Notify("Page was not saved.", "warning", 10000, response.content).create();
            } else {
                new Notify("Fatal error has occurred while saving the page.", "negative", 10000, response.content).create();
            }
        } catch (error) {
            new Notify("An error has occurred.", "negative", 10000, "Page was not saved.").create();
        }

    });

    container.appendChild(saveButton);

    container.style.height = "fit-content";
    container.style.width = "500px"
    container.style.padding = "10px";
    container.style.borderRadius = "var(--border_radius)";
    container.style.background = "var(--primary_background)";
    container.style.display = "flex";
    container.style.flexWrap = "wrap";
    container.style.gap = "30px";
    container.style.justifyContent = "center";
    container.style.alignItems = "center";
    container.style.marginLeft = "auto";
    container.style.marginRight = "auto";
    container.style.marginBottom = "0";
    container.style.marginTop = "0";
    return container;
}

function init() {
    $(document).find(".manage_page").each((index, element) => {
        element.addEventListener("click", function (e) {
            const target = $(e.currentTarget);
            const id = target.attr("data-id");

            const manage = new ManagePage(id);
            manage.getInfo().then(data => {
                const info = JSON.parse(data.content);
                PopulateHelper(`Page manager`, createInput(info));
            });
        });
    });

    $("#createRoute").click((e) => {
        const RouteUi = new CreateRouteUI().render();

        new PopulateHelper("", RouteUi);

    });
}

document.addEventListener("DOMContentLoaded", () => {
    init();
});

