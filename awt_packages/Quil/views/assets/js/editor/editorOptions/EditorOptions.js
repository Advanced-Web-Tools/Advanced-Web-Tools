import {Editor} from "../Editor.js";

export class EditorOptions {
    constructor() {
        this.defaultOptions = {
            autoSave: true,
            autoSaveInterval: 300,
            defaultView: 'desktop',
        };
        this.options = this.loadOptions();
        this.interval = null;
    }

    saveOptions() {
        localStorage.setItem('editorOptions', JSON.stringify(this.options));
    }

    loadOptions() {
        const savedOptions = localStorage.getItem('editorOptions');
        if (savedOptions) {
            try {
                return JSON.parse(savedOptions);
            } catch (e) {
                console.error('Failed to parse editor options from localStorage:', e);
            }
        }
        return { ...this.defaultOptions };
    }

    updateOption(key, value) {
        if (key in this.defaultOptions) {
            this.options[key] = value;
            this.saveOptions();
        } else {
            console.warn(`Option key "${key}" is not valid.`);
        }
    }

    resetOptions() {
        this.options = { ...this.defaultOptions };
        this.saveOptions();
        this.apply();
    }

    apply() {
        if(this.options.autoSave) {
            const urlParams = new URLSearchParams(window.location.search)
            const paramValue = urlParams.get('id');

            if(this.interval !== null)
                clearInterval(this.interval);

            this.interval = setInterval((e) => {
                new Editor().savePage(paramValue);
            }, this.options.autoSaveInterval * 1000);
        }

        if(this.options.defaultView === 'mobile') {
            if(!$(".editor .page").hasClass("mobile"))
                new Editor().mobileView($(".editor .page"));
        } else {
            if($(".editor .page").hasClass("mobile"))
                new Editor().mobileView($(".editor .page"));
        }

    }

    draw() {
        const container = document.createElement("div");
        container.classList.add("editor-options-container");

        container.style = '' +
            'background: var(--primary_background);' +
            'border-radius: var(--border_radius);' +
            'margin: 0 auto;' +
            'padding: 20px;' +
            'display: grid;' +
            'grid-template-columns: auto auto;' +
            'grid-template-auto-rows: auto;' +
            'gap: 10px;' +
            'color: var(--text_primary);';
        container.style.width = '50%';
        container.style.height = 'fit-content';


        const title = document.createElement("h3");
        title.innerHTML = "Editor options <i class=\"fa-solid fa-wrench\"></i>";

        title.style.gridColumnStart = '1';
        title.style.gridColumnEnd = '3';

        container.appendChild(title);

        this.generateFields(container);

        const resetButton = document.createElement("button");
        const applyButton = document.createElement("button");

        resetButton.classList.add('reset-button');
        resetButton.classList.add('btn_action_negative');
        resetButton.classList.add('sm');
        resetButton.innerHTML = 'Reset <i class="fa-solid fa-rotate-right"></i>';

        applyButton.classList.add('apply-button');
        applyButton.classList.add('btn_primary');
        applyButton.classList.add('sm');

        applyButton.innerHTML = 'Apply <i class="fa-solid fa-check"></i>';

        resetButton.style.margin = "0 auto";
        applyButton.style.margin = "0 auto";

        applyButton.addEventListener("click", (e) => {
            this.apply();
        });

        resetButton.addEventListener("click", (e) => {
            this.resetOptions();
        })

        container.appendChild(resetButton);
        container.appendChild(applyButton);

        return container;
    }

    generateFields(container) {
        const camelToLabel = (camelCase) => {
            return camelCase.replace(/([a-z])([A-Z])/g, '$1 $2').replace(/^./, str => str.toUpperCase());
        };

        for (const [key, value] of Object.entries(this.options)) {
            const label = document.createElement('label');
            label.textContent = camelToLabel(key) + ":";
            label.style.margin = "5px";
            label.htmlFor = `option-${key}`;

            let input;
            if (typeof value === 'boolean') {
                input = document.createElement('input');
                input.type = 'checkbox';
                input.checked = value;
                input.id = `option-${key}`;
                input.addEventListener('change', (e) => {
                    this.updateOption(key, e.target.checked);
                });
            } else if (typeof value === 'number') {
                input = document.createElement('input');
                input.type = 'number';
                input.value = value;
                input.id = `option-${key}`;
                input.classList.add('inp_primary');
                input.addEventListener('input', (e) => {
                    this.updateOption(key, Number(e.target.value));
                });
            } else if (typeof value === 'string' && key === 'defaultView') {
                input = document.createElement('select');
                input.id = `option-${key}`;
                input.classList.add('select_primary');

                const options = ['desktop', 'mobile'];
                options.forEach((opt) => {
                    const optionElement = document.createElement('option');
                    optionElement.value = opt;
                    optionElement.textContent = opt;
                    optionElement.selected = opt === value;
                    input.appendChild(optionElement);
                });

                input.addEventListener('change', (e) => {
                    this.updateOption(key, e.target.value);
                });
            }

            if (typeof value !== 'boolean') {
                input.classList.add('lg');
            } else {
                input.style.width = 'fit-content';
            }

            container.appendChild(label);
            container.appendChild(input);
        }
    }
}
