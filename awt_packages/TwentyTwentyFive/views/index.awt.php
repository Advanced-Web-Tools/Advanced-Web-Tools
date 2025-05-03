@extends("TwentyTwentyFive.views.template.main")

@section('head')

@endsection
@section('page')
    <div id="page" class="page">
        <link rel="stylesheet" href="/awt_packages/TwentyTwentyFive/views/assets/css/home.css">
        <section class="hero block"
                 style="background-image: url('/awt_packages/TwentyTwentyFive/views/assets/images/hero.svg');">
            <div class="hero-content block">
                <h1 class="block">2025 Reimagined</h1>
                <p class="block">Build your online presence the right way.</p>
                <a href="/dashboard" class="btn-primary block">Get Started</a>
            </div>
        </section>

        <section class="features block">
            <div class="container block">
                <h2 class="block">What You Get</h2>
                <div class="feature-grid block">
                    <div class="feature block">
                        <h3 class="block">Responsive Design</h3>
                        <p class="block">Looks stunning on all devices and screen sizes.</p>
                    </div>
                    <div class="feature block">
                        <h3 class="block">Fast Performance</h3>
                        <p class="block">Lightweight, fast-loading code optimized for speed.</p>
                    </div>
                    <div class="feature block">
                        <h3 class="block">Modern Tools</h3>
                        <p class="block">Built with modern standards for clean, maintainable code.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="about block">
            <div class="container about-content block">
                <div class="text block">
                    <h2 class="block">About the Theme</h2>
                    <p class="block">TwentyTwentyFive is a professional, minimalist theme designed for startups, personal brands, and
                        creative portfolios. Fully customizable, easy to extend, and built with best practices in
                        mind.</p>
                </div>
            </div>
        </section>

        <section class="cta block">
            <div class="container block">
                <h2 class="block">Ready to Launch?</h2>
                <p class="block">Get started with TwentyTwentyFive today.</p>
                <a href="/dashboard" class="btn-primary block">Go to Dashboard</a>
            </div>
        </section>
    </div>
@endsection
