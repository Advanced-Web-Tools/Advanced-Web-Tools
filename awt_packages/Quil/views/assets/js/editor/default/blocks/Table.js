import { Block } from "../../blocks/Block.js";
import { blocks } from "../../../main.js";

// Funkcija za kreiranje osnovne strukture tabele
function createTableStructure(rows = 3, cols = 3) {
    const tableWrapper = document.createElement("div");
    tableWrapper.classList.add("block", "table-block");

    const table = document.createElement("table");
    const thead = document.createElement("thead");
    const tbody = document.createElement("tbody");

    const headerRow = document.createElement("tr");
    for (let i = 0; i < cols; i++) {
        const th = document.createElement("th");
        th.setAttribute("contenteditable", "true");
        th.innerText = `Header ${i + 1}`;
        headerRow.appendChild(th);
    }
    thead.appendChild(headerRow);

    // Kreiranje tela tabele
    for (let i = 0; i < rows; i++) {
        const bodyRow = document.createElement("tr");
        for (let j = 0; j < cols; j++) {
            const td = document.createElement("td");
            td.setAttribute("contenteditable", "true");
            td.innerText = `Cell Data`;
            bodyRow.appendChild(td);
        }
        tbody.appendChild(bodyRow);
    }

    table.appendChild(thead);
    table.appendChild(tbody);
    tableWrapper.appendChild(table);

    return tableWrapper;
}

// Kreiranje i konfiguracija novog bloka
export let table = new Block();
const tableHtml = createTableStructure();

table.setName("Table");
table.setFaIcon("fa-solid fa-table");
table.setCategory("Layout"); // Smeštanje u istu kategoriju kao i ostali layout blokovi
table.addBody(tableHtml);
blocks.addBlock(table);