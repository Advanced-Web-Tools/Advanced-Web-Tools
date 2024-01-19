function ListOptions($block, defaultStyle) {
    var defaultListStyle = defaultStyle ? defaultStyle.match(/list-style:\s*(\S+);/) : null;
    defaultListStyle = defaultListStyle ? defaultListStyle[1] : "none";

    var options = '<p>List options:</p>';
    options += '<select class="list-style-select">';
    options += '<option value="none">None</option>';
    options += '<option value="ordered">Ordered</option>';
    options += '<option value="unordered">Unordered</option>';
    options += '</select>';
    options += '<button class="add-list-item">Add Item</button>';
    options += '<button class="remove-list-item">Remove Item</button>';

    $(".block-options").append(options);

    // Set the default list style option
    $(".list-style-select").val(defaultListStyle);

    // Event handler for list style selection change
    $(".list-style-select").on("change", function () {
        var selectedOption = $(this).val();
        if (selectedOption === "ordered") {
            $block.css("list-style", "ordered");
        } else if (selectedOption === "unordered") {
            $block.css("list-style", "unordered");
        } else {
            $block.css("list-style", "none");
        }
    });

    // Event handler for adding a new list item
    $(".add-list-item").on("click", function () {
        var newListElement = '<li contenteditable="true">New List Item</li>';
        $block.append(newListElement);
    });

    $(".remove-list-item").on("click", function () {
        $block.children("li").last().remove();
    });
}

function hasListChild($block) {
    return $block.find(":not(:has(*))").filter(function () {
        var listTags = ["ul", "ol", "li", "dl", "dt", "dd"];
        return listTags.includes(this.tagName.toLowerCase());
    });
}


function isList($block)
{
    return hasListChild($block).length !== 0;
}

var option = new BlockOption(isList, ListOptions);
blockOptions.push(option);