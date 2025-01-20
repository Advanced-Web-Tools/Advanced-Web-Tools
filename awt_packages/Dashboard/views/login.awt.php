<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Advanced Web Tools | Login</title>
    <link rel="stylesheet" href="@asset('css/login.css')">
    <link rel="stylesheet" href="@asset('css/main.css')">
    <link rel="stylesheet" href="@resource('fontawesome-free-6.5-web/css/all.css')">
</head>
<body style="background-image: url('@data('pexels-ifreestock-572487.jpg','image')')">
    <div class="login">
        <div class="header">
            <img src="@data('logo-banner-transparent.png','image')" alt="Logo">
            @if($status === "failed" )
                <div class="msg_e">
                    <i class="fa-solid fa-triangle-exclamation"></i><h3> We failed to authenticate you. Please try again.</h3>
                </div>
            @endif
            @if($status === "logout")
                <div class="msg_i">
                    <i class="fa-solid fa-circle-info"></i><h3> You have been signed out.</h3>
                </div>
            @endif
        </div>
        <form action="/dashboard/loginAction" method="post" class="form">
            <label>
                <p class="lg">Username</p>
                <input class="inp_primary lg" type="text" placeholder="username" value="" name="username">
            </label>
            <label>
                <p class="lg">Password</p>
                <input class="inp_primary lg" type="password" placeholder="Password" value="" name="password">
            </label>
            <button type="submit" class="btn_primary md">Login <i class="fa-solid fa-right-to-bracket"></i></button>
        </form>
        <div class="footer lg">
            <p>In order to proceed, please login.</p>
            <a class="hp_primary" href="/dashboard/passwordreset"><i class="fa-solid fa-key"></i> Forgot password?</a>
        </div>
    </div>
</body>
</html>