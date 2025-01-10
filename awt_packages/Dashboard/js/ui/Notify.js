export class Notify {
    constructor(name, status, timeout, message) {
        this.name = name;
        this.state = status;
        this.timeout = timeout;
        this.message = message;
        this.header_color = "--info";

        if (this.state === "positive") {
            this.icon = "fa-check-circle";
            this.header_color = "--info";
        } else if (this.state === "negative") {
            this.icon = "fa-dumpster-fire";
            this.header_color = "--error";
        } else {
            this.icon = "fa-exclamation-triangle";
            this.header_color = "--warning";
        }
    }

    create() {
        const notifyDiv = document.createElement("div");
        notifyDiv.className = "notify-dash shadow";
        notifyDiv.style = `
            position: absolute; 
            bottom: 20px; 
            right: 20px; 
            display: block; 
            z-index: 100; 
            min-width: 300px;
            width: auto;
            min-height: 100px;
            height: auto;
            border: 1px solid var(--primary_background);
            border-radius: var(--border_radius);
        `;

        const headerDiv = document.createElement("div");
        headerDiv.className = "header";
        headerDiv.style = `
            color: var(${this.header_color}); 
            background: var(--primary_background); 
            display: flex; 
            align-items: center; 
            padding: 5px;
            justify-content: space-between;
            border-top-left-radius: var(--border_radius);
            border-top-right-radius: var(--border_radius);
        `;

        const title = document.createElement("h5");
        title.className = "title";
        title.style = "padding: 0; margin: 0 10px 0 10px;";
        title.innerHTML = `<i class="fa-solid ${this.icon}"></i> ${this.name}`;

        const closeButton = document.createElement("p");
        closeButton.className = "close";
        closeButton.innerHTML = `<i style="cursor: pointer; padding: 0; margin: 0 10px 0 10px;" class="fa-solid fa-xmark"></i>`;
        closeButton.onclick = () => notifyDiv.remove();

        headerDiv.appendChild(title);
        headerDiv.appendChild(closeButton);

        const contentDiv = document.createElement("div");
        contentDiv.className = "content";
        contentDiv.style = `
            margin: 0;
            padding: 5px;
            background: var(--secondary_background); 
            color: var(--text_secondary); 
            display: flex; 
            align-items: center; 
            justify-content: space-between;
            border-bottom-left-radius: var(--border_radius);
            border-bottom-right-radius: var(--border_radius);
        `;

        const messageParagraph = document.createElement("p");
        messageParagraph.style = "font-size: .8rem; text-align: left; padding: 0 5px;";
        messageParagraph.innerHTML = this.message;

        contentDiv.appendChild(messageParagraph);

        notifyDiv.appendChild(headerDiv);
        notifyDiv.appendChild(contentDiv);

        document.body.appendChild(notifyDiv);

        if (this.timeout > 0) {
            setTimeout(() => notifyDiv.remove(), this.timeout);
        }
    }
}
