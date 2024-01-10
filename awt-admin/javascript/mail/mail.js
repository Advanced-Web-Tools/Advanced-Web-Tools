function fetchMail() {
    $('.mail-selector p').on("click", function(){

        $('.selected').removeClass('selected');

        $(this).addClass("selected");

        $('.mail-container .mail-wrapper').html(" ");
        $(".mail-container .header").html("<h2>");

        $(".mail-container .header h2").text($(this).text());
        const status = $(this).attr('data-status');
        $.ajax({
            url: './jobs/mail.php',
            type: 'POST',
            data: {
                fetch: status
            },
            success: function (response) {
            },
            error: function (xhr, status, error) {
              console.log(error);
            }

        }).done(function(response) {

            const data = JSON.parse(response);

            $.each(data, function(key, value) {

                var mail = JSON.parse(value);

                const html = $('<div>').addClass('mail');
                const header = $('<h2>').addClass('header');
                const timestamp = $('<p>').addClass('timestamp');

                html.attr("data-mail-id", mail[0]);
            
            
                if (mail[3].trim() == "") {
                    mail[3] = "Subject";
                }
                if (mail[4].trim() == "") {
                    mail[4] = "Content";
                }
            
                header.text(mail[3]);

                timestamp.text(mail[5]);
            
                html.append(header);

                html.append(timestamp);
                
                html.on('click', function(e) {
                    getMessage(this);
                });

                $('.mail-container .mail-wrapper').append(html);
            });
            

        });

    });
}

function testMail()
{
    $.ajax({
        url: './jobs/mail.php',
        type: 'POST',
        data: {
            test: 1
        },
        success: function (response) {
        },
        error: function (xhr, status, error) {
          console.log(error);
        }

    }).done(function(response) {

    });
}

function getMessage(caller)
{
    const message_id = $(caller).attr('data-mail-id');

    $.ajax({
        url: './jobs/mail.php',
        type: 'POST',
        data: {
            load: message_id
        },
        success: function (response) {
        },
        error: function (xhr, status, error) {
          console.log(error);
        }

    }).done(function(response) {
        const data = JSON.parse(response);

        $(".mail-container .header h2").text(data.subject);

        const header = $("<div>").addClass("mail-header");
        const headerSender = $("<p>");
        const headerRecipient = $("<p>");

        headerSender.text("From: " + data.sender);
        headerRecipient.text("To: " + data.recipient);

        header.append(headerSender);
        header.append(headerRecipient)
        header.append("<hr>");
        $(".mail-container .header").append(header);


        $(".mail-container .mail-wrapper").html(data.content);
    });

}

function sendMail() {
    const recipient = $('.dialog #recipient').val();
    const subject = $('.dialog #subject').val();
    const content = $('.dialog #content').val();

    $.ajax({
        url: './jobs/mail.php',
        type: 'POST',
        data: {
            send: 1,
            recipient: recipient,
            subject: subject,
            content, content
        },

         error: function () {
            console.log('AJAX request failed.');
        }

    }).done(function (data) {

        var html = "";
        var response = JSON.parse(data);

        if(!response) {
            response = "Failed to send";
        } else {
            response = "Message sent"
        }
        $(".info").removeClass("hidden");
        $(".info").html("<p>"+response+"</p>");
        setTimeout(function() {

            if(!$(".info").hasClass("hidden")) {
                $(".info").addClass("hidden");
            }
            
        }, 2000);

        console.log(response)
        
    });

}
$(document).ready(function () {
    fetchMail();

    $('.selected').click();

    $('#testMail').on('click', function() {
        testMail();
    });

    $('button.compose').on('click', function() {

        $('.dialog').toggleClass('hidden');

    });

});