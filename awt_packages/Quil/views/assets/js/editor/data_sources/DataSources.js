import { AWTRespondRequest } from "../../../../../../AWTRespond/js/AWTRespond.js";
import { Notify } from "../../../../../../Dashboard/js/ui/Notify.js";

export class DataSources {
    constructor(id) {
        this.id = id;
        this.sources = {};
        this.tables = {};
    }

    async fetchDataSources() {
        try {
            const api = new AWTRespondRequest('');
            const data = await api.get(`/quil/datasources/${this.id}`);
            this.sources = Object.values(data.sources);
            this.tables = data.tables;
        } catch (error) {
            new Notify('Unknown error has occurred!', 'negative', 5000, 'Please try again later.').create();
            console.error(error);
        }
    }

    async appendNewSource(sourceId) {
        await this.fetchDataSources();
        const newSource = this.sources.find(source => source.id === sourceId);
        if (newSource) {
            const container = this.drawSourceContainer(newSource);
            const parentContainer = document.querySelector(".dataSourcesContainer");
            if (parentContainer) parentContainer.appendChild(container);
        }
    }

    async updateSourceContainer(sourceId) {
        await this.fetchDataSources();
        const updatedSource = this.sources.find(source => source.id === sourceId);
        const container = document.querySelector(`[data-source-id="${sourceId}"]`);
        if (container && updatedSource) {
            const newContainer = this.drawSourceContainer(updatedSource);
            container.replaceWith(newContainer);
        }
    }

    drawSourceContainer(source) {
        const container = this.createSourceSelector({ existing: true, source });
        container.dataset.sourceId = source.id;
        this.populateSourceSelector(container, source);
        return container;
    }

    drawHelper(node) {
        const container = this.createContainer("Page data sources");

        this.sources.forEach((source) => {
            container.appendChild(this.drawSourceContainer(source));
        });

        const addSourceButton = this.createButton('Add Source', 'addSourceButton btn_primary sm', () => {
            container.insertBefore(this.createSourceSelector(), addSourceButton);
        });

        container.appendChild(addSourceButton);
        return container;
    }

    createSourceSelector(props = null) {
        const container = this.createBaseSelectorContainer();
        const selectTable = this.createDropdown('selectTable select_primary sm', "Select a source.");
        const selectColumn = this.createDropdown('selectColumn select_primary sm', "Data selector.");

        this.populateTableSelector(selectTable);

        selectTable.addEventListener("change", () => {
            this.drawColumnSelector(selectTable, selectColumn);
        });

        const dynamicValue = this.createInputField('dynamicValue inp_primary sm', "Parameter from path.", "Ex. /view/Page/{id}. ID is a parameter.");
        const defaultValue = this.createInputField('defaultValue inp_primary sm', "Default value", "A backup for dynamic value.");
        const name = this.createInputField('name inp_primary sm', "Name used for data source.", "Used to store Model in Variable.");
        container.append(selectTable, selectColumn, dynamicValue, defaultValue, name);
        this.appendControlButtons(container, props, { selectTable, selectColumn, dynamicValue, defaultValue, name });


        return container;
    }

    drawColumnSelector(tableSelector, columnSelector) {
        columnSelector.innerHTML = '';
        const selectedTable = this.tables.find((table) => table.table_id === parseInt(tableSelector.value));

        this.addDropdownOption(columnSelector, null, "Data selector.");

        if (selectedTable) {
            selectedTable.columns.forEach((column) => {
                this.addDropdownOption(columnSelector, column.column_name, column.column_name);
            });
        }
    }

    createContainer(titleText) {
        const container = document.createElement("div");
        Object.assign(container.style, {
            display: 'flex',
            flexDirection: 'column',
            justifyContent: 'center',
            alignItems: 'center',
            gap: '10px',
            width: '90%',
            height: 'fit-content',
            background: 'var(--primary_background)',
            borderRadius: 'var(--border_radius)',
            padding: '10px',
            margin: '0 auto',
            color: 'var(--text_primary)',
        });

        const title = document.createElement("h3");
        title.textContent = titleText;
        container.appendChild(title);

        return container;
    }

    createButton(text, className, onClick) {
        const button = document.createElement("button");
        button.className = className;
        button.textContent = text;
        button.addEventListener("click", onClick);
        return button;
    }

    createBaseSelectorContainer() {
        const container = document.createElement("div");
        Object.assign(container.style, {
            display: 'flex',
            flexWrap: 'wrap',
            justifyContent: 'center',
            alignItems: 'center',
            gap: '10px',
            width: '100%',
        });
        return container;
    }

    createDropdown(className, defaultText) {
        const dropdown = document.createElement("select");
        dropdown.className = className;
        this.addDropdownOption(dropdown, null, defaultText);
        return dropdown;
    }

    createInputField(className, placeholder, title) {
        const input = document.createElement("input");
        input.className = className;
        input.type = 'text';
        input.style.width = 'fit-content';
        input.placeholder = placeholder;
        input.title = title;
        return input;
    }

    populateTableSelector(selectTable) {
        this.tables.forEach((table) => {
            this.addDropdownOption(selectTable, table.table_id, table.name);
        });
    }

    addDropdownOption(dropdown, value, text) {
        const option = document.createElement("option");
        option.value = value;
        option.textContent = text;
        dropdown.appendChild(option);
    }

    appendControlButtons(container, props, fields) {
        const { selectTable, selectColumn, dynamicValue, defaultValue, name } = fields;

        const isEditMode = props !== null;

        const actionButton = this.createButton(isEditMode ? "Update" : "Save", isEditMode ? 'saveButton btn_primary' : 'saveButton btn_secondary', async () => {
            if (!this.validateFields(fields)) {
                new Notify('All values need to be filled.', 'warning', 5000, 'All parameters need to be filled.').create();
                return;
            }

            try {
                const api = new AWTRespondRequest('');
                const url = isEditMode ? `/quil/update_source/${this.id}` : `/quil/add_source/${this.id}`;
                const payload = {
                    id: props?.source?.id,
                    table_id: selectTable.value,
                    column: selectColumn.value,
                    url_param: dynamicValue.value,
                    defaultValue: defaultValue.value,
                    name: name.value,
                };

                const data = await api.post(url, payload);
                const notifyType = data.code === 200 ? "positive" : "negative";
                const notifyText = isEditMode ? "Source updated!" : "Source added!";

                new Notify(notifyText, notifyType, 5000, notifyText).create();

                if (data.code === 200) {
                    await this.fetchDataSources();
                    if (isEditMode) await this.updateSourceContainer(props.source.id);
                    else await this.appendNewSource(data.source_id);
                }
            } catch (error) {
                new Notify('Unknown error has occurred!', 'negative', 5000, 'Please try again later.').create();
                console.error(error);
            }
        });

        container.appendChild(actionButton);

        if (isEditMode) {
            const deleteButton = this.createButton("Delete", 'deleteButton btn_action_negative', async () => {
                try {
                    const api = new AWTRespondRequest('');
                    const data = await api.post(`/quil/delete_source/${this.id}`, { id: props.source.id });
                    const notifyType = data.code === 200 ? "positive" : "negative";
                    new Notify("Source deleted!", notifyType, 5000, "Source deleted!").create();
                    if (data.code === 200) {
                        await this.fetchDataSources();
                        const sourceContainer = document.querySelector(`[data-source-id="${props.source.id}"]`);
                        if (sourceContainer) sourceContainer.remove();
                    }
                } catch (error) {
                    new Notify('Unknown error has occurred!', 'negative', 5000, 'Please try again later.').create();
                    console.error(error);
                }
            });
            container.appendChild(deleteButton);
        }
    }

    validateFields(fields) {
        return Object.values(fields).every(field => field.value?.trim());
    }

    populateSourceSelector(container, source) {
        const selectTable = container.querySelector('.selectTable');
        const selectColumn = container.querySelector('.selectColumn');

        selectTable.value = source.table_id;
        this.drawColumnSelector(selectTable, selectColumn);

        selectColumn.value = source.column_selector;
        container.querySelector('.defaultValue').value = source.default_param_value;
        container.querySelector('.dynamicValue').value = source.bind_param_url;
        container.querySelector('.name').value = source.source_name;
    }
}
