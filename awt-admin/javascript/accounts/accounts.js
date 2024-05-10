function fetchAccounts(container) {

    $.ajax({
        url: './jobs/accounts.php',
        type: 'POST',
        data: {
            get_accounts: 1
        },
         error: function () {
            console.log('AJAX request failed.');
        }

    }).done(function (data) {

        var html = "";
        var response = JSON.parse(data);
        $.each(response, function (index, value) {
            html += "<div class='account shadow'><span><h4>" + value.firstname + " " + value.lastname + "</h4>";
            html += "<p>@" + value.username + "</p>";
            var params = value.id+",'"+container+"'";
            html += '</span><span><button class="button" id="green" data-email="' + value.email + '" onclick="sendMailDialog(this)"><i class="fa-solid fa-envelope"></i> Send email</button>';
            html += '</span><span><button class="button" id="red" onclick="deleteAccount('+params+');">Delete</button>';
            html += "</span></div>";
        });

        $(container).html(html);
        
    });
}

function createAccount(container) {
    var email = $(".email-create").val();
    var username = $(".username-create").val();
    var firstname = $(".fname-create").val();
    var lastname = $(".lname-create").val();
    var password = $(".password-create").val();
    var permission = $(".permissionLevel-create").val();

    $.ajax({
        url: "./jobs/accounts.php",
        method: "POST",
        data: {
            create_account: 1, 
            email: email,
            username: username,
            firstname: firstname,
            lastname: lastname,
            password: password,
            permission: permission
        },
        success: function(response) {
            fetchAccounts(container);
            $(".info").removeClass("hidden");

            response = response.replace(/"/g, ' ');
            $(".info").html("<p>"+response+"</p>");
            setTimeout(function() {

                if(!$(".info").hasClass("hidden")) {
                    $(".info").addClass("hidden");
                }

            }, 2000);
        },
        error: function(xhr, status, error) {
        }
    });
}

function deleteAccount(id, container) {
    $.ajax({
        url: "./jobs/accounts.php",
        method: "POST",
        data: {
            delete_account: id, 
        },
        success: function(response) {
            fetchAccounts(container);
            $(".info").removeClass("hidden");
            response = response.replace(/"/g, ' ');
            $(".info").html("<p>"+response+"</p>");
            setTimeout(function() {

                if(!$(".info").hasClass("hidden")) {
                    $(".info").addClass("hidden");
                }
                
            }, 2000);
        },
        error: function(xhr, status, error) {
            console.log('AJAX request failed.');
        }
    });
}

function sendMailDialog(caller) {
    const reciever = $(caller).attr('data-email');
    $('.dialog').removeClass('hidden');

    $('.dialog #recipient').val(reciever);
}


function changeInfo()
{
    var email = $(".edit-account .email").val();
    var firstname = $(".edit-account .fname").val();
    var lastname = $(".edit-account .lname").val();
    var password = $(".edit-account .password").val();

    $.ajax({
        url: "./jobs/accounts.php",
        method: "POST",
        data: {
            edit_info: 1, 
            email: email,
            firstname: firstname,
            lastname: lastname,
            password: password,
        },
        success: function(response) {
            $(".info").removeClass("hidden");

            console.log(response)

            response = response.replace(/"/g, ' ');
            $(".info").html("<p>"+response+"</p>");
            setTimeout(function() {

                if(!$(".info").hasClass("hidden")) {
                    $(".info").addClass("hidden");
                }

            }, 2000);
        },
        error: function(xhr, status, error) {
        }
    });
}