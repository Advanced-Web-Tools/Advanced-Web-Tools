export function ExtractUnitAndValue(block, style) {
    const value = block[0].style[style] || "0px";

    if (value === "auto" || value === "inherit" || value === "initial" || value === "unset") {
        return [0, "px"];
    }

    const numericValue = parseFloat(value);
    const unit = value.match(/[a-zA-Z%]+/) ? value.match(/[a-zA-Z%]+/)[0] : "px";

    return [numericValue, unit];
}


export function rgbToHex(rgb) {

    const result = rgb.match(/\d+/g);

    if (!result || result.length < 3) {
        throw new Error("Invalid RGB format");
    }

    const r = parseInt(result[0]).toString(16).padStart(2, '0');
    const g = parseInt(result[1]).toString(16).padStart(2, '0');
    const b = parseInt(result[2]).toString(16).padStart(2, '0');

    return `#${r}${g}${b}`;
}
