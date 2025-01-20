<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> {{ $name }} {{ $theme->settings['Title']->value }} {{ $page }}</title>
    @yield('head')
</head>
<body>
    @yield('page')
</body>
</html>