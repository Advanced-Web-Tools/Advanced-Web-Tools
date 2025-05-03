import {AWTRespondRequest} from "../../../../AWTRespond/js/AWTRespond.js";
import {Notify} from "../../../../Dashboard/js/ui/Notify.js";

document.addEventListener("DOMContentLoaded", function () {
    const menus = document.querySelectorAll(".menu");
    const items = document.querySelectorAll(".item");
    const addItemButton = document.getElementById("addItemButton");

    let activeMenuId = null;

    menus.forEach(menu => {
        const checkbox = menu.querySelector("input.menu_select");

        if (checkbox && checkbox.checked) {
            activeMenuId = checkbox.dataset.id;
            menu.classList.add("active");

            items.forEach(item => {
                if (item.dataset.menuId === activeMenuId) {
                    item.classList.remove("hidden");
                } else {
                    item.classList.add("hidden");
                }
            });
        }
    });

    menus.forEach(menu => {
        menu.addEventListener("click", () => {
            menus.forEach(m => m.classList.remove("active"));

            menu.classList.add("active");
            activeMenuId = menu.querySelector("input.menu_select").dataset.id;

            items.forEach(item => {
                if (item.dataset.menuId === activeMenuId) {
                    item.classList.remove("hidden");
                } else {
                    item.classList.add("hidden");
                }
            });
        });
    });

    addItemButton.addEventListener("click", () => {
        if (!activeMenuId) {
            alert("Please select a menu first.");
            return;
        }

        const newItemId = `new-${Date.now()}`;

        const newItem = document.createElement("div");
        newItem.className = "item";
        newItem.dataset.menuId = activeMenuId;
        newItem.dataset.id = newItemId;

        newItem.innerHTML = `
            <p class="identifier">Item ID: ${newItemId}</p>
            <label for="name_${newItemId}">Text:</label>
            <input id="name_${newItemId}" type="text" class="inp_primary name" value="">
            <label for="link_${newItemId}">Location:</label>
            <input id="link_${newItemId}" type="text" class="inp_primary link" value="">
            <label for="target_${newItemId}">Target:</label>
            <select name="target" id="target_${newItemId}" class="select_primary" style="width: 100px;">
                <option value="null">Select target</option>
                <option value="_self">Current</option>
                <option value="_blank">New Tab</option>
            </select>
            <label for="parent_${newItemId}">Parent:</label>
            <select name="parent" id="parent_${newItemId}" class="select_primary" style="width: 100px;">
                <option value="null">Select parent item</option>
                ${Array.from(items)
            .filter(item => item.dataset.menuId === activeMenuId)
            .map(item => `<option value="${item.dataset.id}">${item.dataset.id}</option>`)
            .join("")}
            </select>
            <label for="position_${newItemId}">Position:</label>
            <input min="0" type="number" name="order" id="position_${newItemId}" class="inp_primary" style="width: 30px;" value="1">
            <button class="btn_primary save_item" data-id="${newItemId}"><i class="no_mrg fa-solid fa-save"></i></button>
            <button class="btn_action_negative delete_item" data-id="${newItemId}"><i class="no_mrg fa-solid fa-trash-can"></i></button>
        `;

        newItem.querySelector(".save_item").addEventListener("click", (e) => { save(e) });

        document.querySelector(".items").appendChild(newItem);
    });

    document.querySelectorAll(".save_item").forEach((item, key) => {
        item.addEventListener("click", (e) => {
            save(e);
        });
    });

    document.querySelectorAll(".delete_item").forEach((item, key) => {
        item.addEventListener("click", (e) => {
            deleteItem(e);
        });
    });
});


function save(e) {
    const api = new AWTRespondRequest("");

    const id = e.currentTarget.dataset.id;

    const targetElement = document.querySelector("select#target_" + id);
    const nameElement = document.querySelector("input#name_" + id);
    const locationElement = document.querySelector("input#link_" + id);
    const parentElement = document.querySelector("select#parent_" + id);
    const position = document.querySelector("input#position_" + id);
    let parentID = null;

    if(parentElement.value !== "null") {
        parentID = parentElement.value;
    }

    let parent = e.currentTarget.parentNode;

    const div = document.querySelector(".item[data-id='" + id + "']");

    api.sendRequest("/theming/menu/save", "POST", {
        id: id,
        menu_id: div.dataset.menuId,
        target: targetElement ? targetElement.value : null,
        name: nameElement ? nameElement.value : null,
        link: locationElement ? locationElement.value : null,
        parent_id: parentID,
        position: position.value
    }).then((data) => {
        if (data.response === 200) {
            const notify = new Notify("Menu item saved", "positive", 3000, "Item was saved successfully!");
            notify.create();
            if(data.id) {
                parent.querySelector(".identifier").textContent = "Item ID: " + data.id;
            }
        } else {
            const notify = new Notify("Unknown error", "negative", 3000, "An unknown error has occurred while saving the item.");
            notify.create();
        }
    });
}

function deleteItem(e) {
    const api = new AWTRespondRequest("");

    const id = e.currentTarget.dataset.id;
    let parentElement = e.currentTarget.parentElement;

    api.sendRequest("/theming/menu/delete", "POST", { id: id }).then((data) => {
        if (data.response === 200) {
            const notify = new Notify("Menu item deleted", "positive", 3000, "Item was deleted!");
            notify.create();

            if (parentElement) {
                parentElement.outerHTML = "";
            }

        } else {
            const notify = new Notify("Unknown error", "negative", 3000, "An unknown error has occurred while deleting the item.");
            notify.create();
        }
    }).catch((error) => {
        console.error("Error deleting item:", error);
    });
}
