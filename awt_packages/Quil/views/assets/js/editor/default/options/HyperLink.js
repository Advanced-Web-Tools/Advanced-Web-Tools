import {Option} from "../../options/Option.js";
import {CreateContainerComponent} from "../../ui/Container.js";
import {CreateInputComponent, CreateInputWithUnitComponent} from "../../ui/Inputs.js";


export let HyperLink = new Option();

let init = false;

HyperLink.setCategory("Hyperlink");


let options = ["#", "https://", "http://", "mailto:", "tel:", "sms:", "ftp://", "skype:", "whatsapp:"];

const container = CreateContainerComponent("Hyperlink options", true);
CreateInputWithUnitComponent(
    container,
    "text",
    "domain.com, admin@domain.com, 001 1234 567",
    "Enter URL",
    "hyperlink_input",
    "",
    options,
    "Enter URL."
);

const inputUnit = $(container).find(".input-unit");
const input = inputUnit.find("input#hyperlink_input")
const optionDiv = inputUnit.find(".option");

optionDiv.insertBefore(input);


HyperLink.addDrawable(0, container);

HyperLink.setCallableCondition((e) => {
    const block = HyperLink.current_block;
    if (block && block.is("a")) {
        return true;
    } else {
        return false;
    }
});

HyperLink.attachFunction(0, (block) => {
    const current_url = block.attr("href") || "";
    const input = $("#hyperlink_input");
    const unit = $("#hyperlink_input-unit");


    let selectedPrefix = "";
    let urlPart = current_url;

    for (const option of options) {
        if (current_url.startsWith(option)) {
            selectedPrefix = option;
            urlPart = current_url.slice(option.length);
            break;
        }
    }

    unit.val(selectedPrefix);
    input.val(urlPart);

    input[0].style.setProperty('width', '110px', 'important');
    input[0].style.setProperty('text-align', 'left');

    const updateHref = () => {
        if (block) {
            block.attr("href", unit.val() + input.val());
        }
    };

    input.on("input", updateHref);
    unit.on("change", updateHref);
});


