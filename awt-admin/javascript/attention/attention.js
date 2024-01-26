
$(document).ready(function () {
    getAttention();
});

function getAttention() {
    $.ajax({
        type: "POST",
        url: "./jobs/attention.php",
        data: {
            getAttention: 1
        },
        success: function (response) {

        }
    }).done(function (response) {
        const data = JSON.parse(response);
        console.log(data);

        if(data.length > 0) $(".attention-list p").remove();

        $.each(data, function (index, value) {
            const container = $("<div>");
            container.addClass("attention-item");
            container.attr("data-id", value.id);

            const caller = $("<h5>").text(value.caller);

            const message = $("<p>").html(value.reason);

            const solveButton = $("<button>");

            solveButton.addClass("button");
            solveButton.attr("data-id", value.id);

            solveButton.on("click", (e) => {
                const caller = e.target;
                setAsSolved(caller);
            });

            solveButton.text("Mark as solved");

            container.append(caller);
            container.append(message);
            container.append(solveButton);

            $(".attention-list").append(container);

        });

    });
}

function setAsSolved(caller) {
    const id = $(caller).attr("data-id");

    $(".attention-list .attention-item[data-id='" + id + "']").remove();

    $.ajax({
        type: "POST",
        url: "./jobs/attention.php",
        data: {
            setAsSolved: id
        },
        success: function (response) {

        }
    });

}