import { CreateContainerComponent } from "./Container";
import { CreateInputWithUnitComponent } from "./Inputs";

export function createLockedInputGroup(
    html,
    sectionHeader,
    sides,
    inputType = "number",
    botLimit = 0,
    topLimit = 100000,
    additionalUnits = ["px", "%", "em", "rem"]
) {
    const formattedSectionHeader = sectionHeader.replace(/\s+/g, "-").toLowerCase();

    const container = CreateContainerComponent(sectionHeader);
    container.classList.add("locked-input-group");
    container.classList.remove("options-container");

    const lockWrapper = document.createElement("div");
    lockWrapper.classList.add("lock-wrapper");

    let lockIcon = document.createElement("i");
    lockIcon.classList.add("fa", "fa-unlock");

    let isLocked = false;

    lockWrapper.appendChild(lockIcon);

    sides.forEach((side, index) => {
        const formattedSide = side.replace(/\s+/g, "-").toLowerCase();

        CreateInputWithUnitComponent(
            container,
            inputType,
            `${side} value`,
            `${side}:`,
            `${formattedSectionHeader}-${formattedSide}`,
            botLimit.toString(),
            additionalUnits
        );

        const input = container.querySelector(`#${formattedSectionHeader}-${formattedSide}`);
        const unitSelect = container.querySelector(`#${formattedSectionHeader}-${formattedSide}-unit`);

        const syncInputs = (value) => {
            sides.slice(1).forEach((otherSide) => {
                const otherInput = container.querySelector(`#${formattedSectionHeader}-${otherSide.replace(/\s+/g, "-").toLowerCase()}`);
                if (otherInput) {
                    otherInput.value = value;
                    const event = new Event('input', { bubbles: true });
                    otherInput.dispatchEvent(event);
                }
            });
        };

        const syncUnitSelects = (unit) => {
            sides.slice(1).forEach((otherSide) => {
                const otherUnitSelect = container.querySelector(`#${formattedSectionHeader}-${otherSide.replace(/\s+/g, "-").toLowerCase()}-unit`);
                if (otherUnitSelect) {
                    otherUnitSelect.value = unit;
                    const event = new Event('change', { bubbles: true });
                    otherUnitSelect.dispatchEvent(event);
                }
            });
        };

        input.addEventListener("input", (e) => {
            if (isLocked && index === 0) {
                syncInputs(e.target.value);
            }
        });

        unitSelect.addEventListener("change", (e) => {
            if (isLocked && index === 0) {
                syncUnitSelects(e.target.value);
            }
        });

        if (index > 0) {
            input.disabled = isLocked;
            unitSelect.disabled = isLocked;
        }

        if ((sides.length === 2 && index === 0) || (sides.length > 2 && index === Math.floor(sides.length / 2) - 1)) {
            container.appendChild(lockWrapper);
        }
    });

    container.querySelector(".lock-wrapper i").addEventListener("click", (e) => {
        isLocked = !isLocked;
        console.log("clicked icon lock");
        lockIcon.classList.toggle("fa-lock");
        lockIcon.classList.toggle("fa-unlock");

        sides.forEach((side, index) => {
            const formattedSide = side.replace(/\s+/g, "-").toLowerCase();
            const input = container.querySelector(`#${formattedSectionHeader}-${formattedSide}`);
            const unitSelect = container.querySelector(`#${formattedSectionHeader}-${formattedSide}-unit`);

            if (input && unitSelect) {
                input.disabled = isLocked && index > 0;
                unitSelect.disabled = isLocked && index > 0;

                if (isLocked) {
                    const firstInputValue = container.querySelector(`#${formattedSectionHeader}-${sides[0].replace(/\s+/g, "-").toLowerCase()}`).value;
                    const firstUnitValue = container.querySelector(`#${formattedSectionHeader}-${sides[0].replace(/\s+/g, "-").toLowerCase()}-unit`).value;

                    input.value = firstInputValue;
                    unitSelect.value = firstUnitValue;

                    const inputEvent = new Event('input', { bubbles: true });
                    input.dispatchEvent(inputEvent);
                    const unitEvent = new Event('change', { bubbles: true });
                    unitSelect.dispatchEvent(unitEvent);
                }
            }
        });
    });

    html.appendChild(container);

    return html;
}
