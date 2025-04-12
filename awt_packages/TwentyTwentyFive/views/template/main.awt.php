<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> {{ $name }} {{ $theme->settings['Title']->value }} {{ $page }}</title>
    @yield('head')
    <style>
        :root {
            @foreach($theme->settings as $setting)
                    @if($setting->type === "COLOR")
                        --{{$setting->name}}: {{$setting->value}};
                    @endif
                    @if($setting->name === "font_family")
                        --font_family: {!! $setting->value !!};
                    @endif
            @endforeach
        }
    </style>
    <link rel="stylesheet" href="@asset('css/main.css')">
    <link rel="stylesheet" href="@asset('css/navigation.css')">
    <link rel="stylesheet" href="@asset('css/footer.css')">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Roboto:wght@300;400&display=swap"
          rel="stylesheet">
</head>
<body>
<nav class="header">
    <div class="branding">
        <h3>{{ $name }}</h3>
    </div>
    {!! $navigation !!}
</nav>
@yield('page')
<footer class="footer block">
    <div class="container block">
        <div class="footer-content block">
            <div class="footer-branding block">
                <h3 class="block">{{ $name }}</h3>
                <p class="block">Empowering your digital future.</p>
            </div>

            <div class="footer-links block">
                {!! $navigation !!}
            </div>

            <div class="footer-social block">
                <a href="{{ $xSocial }}" class="block">X</a>
                <a href="{{ $linkedinSocial }}" class="block">LinkedIn</a>
                <a href="{{ $instagramSocial }}" class="block">Instagram</a>
            </div>
        </div>

        <div class="footer-bottom block">
            <p class="block">© {{ date('Y') }} {{ $name }}. All rights reserved.</p>
        </div>
    </div>
</footer>
</body>
</html>