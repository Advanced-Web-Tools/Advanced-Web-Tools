<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Advanced Web Tools | {{ title }}</title>
    <link rel="stylesheet" href="@url('awt_packages/Dashboard/views/assets/css/dashboard.css')">
    <link rel="stylesheet" href="@url('awt_packages/Dashboard/views/assets/css/main.css')">
    <link rel="stylesheet" href="@url('awt_packages/Dashboard/views/assets/css/navigation.css')">
    <link rel="stylesheet" href="@resource('fontawesome-free-6.5-web/css/all.css')">
    <script src="@resource('jQuery/jquery.min.js')"></script>
    <script src="@url('awt_packages/Dashboard/views/assets/js/navbar/navigation.js')"></script>
    @yield("head")
</head>
<body>
    <header class="top-bar">
        <div class="branding">
            <img src="@url('awt_data/media/packages/image/Dashboard/logo-banner-transparent.png')" alt="Logo">
        </div>
        <div class="widgets">
            @yield('topbar.widgets')
        </div>
        <div class="profile">
            <img src="@urlVar('admin.profile_picture')" alt="Profile picture" class="p_picture">
            <div class="info shadow">
                <p>{{admin.firstname}} {{admin.lastname}}</p>
                <p>Username: {{admin.username}}</p>
                <p>Role: {{admin.role}}</p>
                <div class="profile_actions">
                    <a href="/dashboard/logout"><button class="btn_primary">Logout <i class="fa-solid fa-right-from-bracket"></i></button></a>
                </div>
            </div>
        </div>
    </header>
    <section class="main">
        <aside class="sidebar shadow">
            <nav class="navigation">
                @yield('nav.top')
                {{ navigation }}
                @yield('nav.bottom')
            </nav>
            @yield('sidebar')
        </aside>
        <section class="page">
            @yield('page')
        </section>
    </section>
</body>
</html>