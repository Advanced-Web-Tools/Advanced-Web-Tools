export class CreateRouteUI {
    constructor() {
        // Initialize the container and form elements
        this.container = document.createElement("div");
        this.container.className = "newRouteContainer";

        this.title = document.createElement("h3");
        this.title.id = "title";
        this.title.className = "title";
        this.title.textContent = "Create new route";

        this.form = document.createElement("form");
        this.form.className = "route_create";
        this.form.action = "/quil/route_create";
        this.form.method = "POST";

        this.addNewInput = document.createElement("button");
        this.addNewInput.id = "addNewInput";
        this.addNewInput.innerHTML = "New Segment<i class=\"fa-solid fa-plus\"></i>";
        this.addNewInput.className = "btn_secondary";


        this.saveRoute = document.createElement("button");
        this.saveRoute.id = "saveRoute";
        this.saveRoute.innerHTML = "Save <i class=\"fa-solid fa-floppy-disk\"></i>";
        this.saveRoute.className = "btn_primary";

        this.inputContainerTemplate = document.createElement("div");
        this.inputContainerTemplate.className = "inputContainer";

        const inputGroup = document.createElement("div");
        inputGroup.className = "inputGroup";

        const input = document.createElement("input");
        input.type = "text";
        input.className = "inp_primary md";
        input.placeholder = "Segment ex. page";
        inputGroup.appendChild(input);

        const paramCheck = document.createElement("input");
        paramCheck.type = "checkbox";
        paramCheck.className = "paramCheck";

        const label = document.createElement("label");
        label.textContent = "Set as parameter";

        label.appendChild(paramCheck);
        inputGroup.appendChild(label);

        const deleteSegment = document.createElement("button");
        deleteSegment.className = "btn_action_negative delete";
        deleteSegment.innerHTML = "Delete <i class='fas fa-trash-can'></i>";
        deleteSegment.type = "button";

        inputGroup.appendChild(deleteSegment);

        // Bind the delete button event after the input group is created
        this.bindDeleteButton(deleteSegment, inputGroup);

        this.hiddenInput = document.createElement("input");
        this.hiddenInput.id = "hiddenInput";
        this.hiddenInput.className = "hidden";
        this.hiddenInput.name = "route_path";

        this.inputContainerTemplate.appendChild(inputGroup);

        // Bind events
        this.bindEvents();
    }

    bindDeleteButton(deleteButton, inputGroup) {
        // Attach the event listener to the delete button
        deleteButton.addEventListener("click", (event) => {
            event.preventDefault();
            inputGroup.remove(); // Remove just the input group
        });
    }

    bindEvents() {

        this.addNewInput.addEventListener("click", (event) => {
            event.preventDefault();

            // Clone the input container template
            const clonedInputContainer = this.inputContainerTemplate.cloneNode(true);

            // Clear cloned input values
            const inputs = clonedInputContainer.querySelectorAll("input");
            inputs.forEach(input => {
                if (input.type === "text") input.value = "";
                if (input.type === "checkbox") input.checked = false;
            });

            // Bind the delete button for the cloned input group
            const deleteButton = clonedInputContainer.querySelector(".delete");
            const inputGroup = clonedInputContainer.querySelector(".inputGroup");
            this.bindDeleteButton(deleteButton, inputGroup);

            // Append the cloned container
            this.form.insertBefore(clonedInputContainer, this.addNewInput);
        });

        this.saveRoute.addEventListener("click", (event) => {
            event.preventDefault();

            const container = document.querySelector(".newRouteContainer");
            this.hiddenInput.value = "";
            const inputs = container.querySelectorAll(".inputGroup");
            inputs.forEach((input, index) => {
                const param = input.querySelector(".paramCheck").checked;

                let route = "";

                if (param) {
                    if (input.querySelector("input").value.trim())
                        route = "{" + input.querySelector("input").value.trim() + "}";
                } else {
                    route = input.querySelector("input").value.trim();
                }

                if (route) {
                    this.hiddenInput.value += route;
                    if (index !== inputs.length - 1) {
                        this.hiddenInput.value += "/";
                    }
                }
            });

            if (this.hiddenInput.value.trim() !== "") {
                this.hiddenInput.value = "/" + this.hiddenInput.value;
                this.form.submit();
            }
        });

    }

    render() {
        // Append the title to the container
        this.container.appendChild(this.title);

        const initialInputContainer = this.inputContainerTemplate.cloneNode(true);
        this.form.appendChild(initialInputContainer);

        const initialDeleteButton = initialInputContainer.querySelector(".delete");
        const initialInputGroup = initialInputContainer.querySelector(".inputGroup");
        this.bindDeleteButton(initialDeleteButton, initialInputGroup);

        this.form.appendChild(this.addNewInput);
        this.form.appendChild(this.saveRoute);
        this.form.appendChild(this.hiddenInput);
        this.container.appendChild(this.form);

        return this.container;
    }
}
