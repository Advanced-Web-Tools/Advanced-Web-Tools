@extends("TwentyTwentyFive.views.template.main")

@section('head')

@endsection

@section('page')
    <link rel="stylesheet" href="/awt_packages/TwentyTwentyFive/views/assets/css/about.css">
    <section class="hero-about block" style="background-image: url('/awt_packages/TwentyTwentyFive/views/assets/images/hero.svg');">
        <div class="hero-about-content block">
            <h1 class="block hero-heading">About Us</h1>
            <p class="block hero-subheading">Get to know who we are and what drives us.</p>
        </div>
    </section>

    {{-- About Us Content --}}
    <section class="about-us block">
        <div class="container about-us-content block">
            <div class="text block">
                <h2 class="block">Who We Are</h2>
                <p class="block">We are a team of passionate professionals committed to creating meaningful digital experiences. We design and build websites that are functional, beautiful, and user-friendly, helping businesses succeed in the digital world.</p>
                <p class="block">Our mission is to empower individuals and organizations by providing high-quality, easy-to-use digital solutions. From startups to established businesses, we work closely with our clients to create personalized experiences that drive growth and success.</p>
            </div>
            <div class="image block">
                <img class="block hover-effect" src="/awt_packages/TwentyTwentyFive/views/assets/images/team.jpg" alt="Our team">
            </div>
        </div>
    </section>

    {{-- Our Values Section --}}
    <section class="values block">
        <div class="container block">
            <h2 class="block values-heading">Our Core Values</h2>
            <div class="values-grid block">
                <div class="value block">
                    <h3 class="value-heading">Innovation</h3>
                    <p class="block">We constantly seek out the latest trends and technologies to deliver cutting-edge solutions that stand the test of time.</p>
                </div>
                <div class="value block">
                    <h3 class="value-heading">Collaboration</h3>
                    <p class="block">We believe in the power of collaboration and work closely with our clients to ensure their vision becomes a reality.</p>
                </div>
                <div class="value block">
                    <h3 class="value-heading">Integrity</h3>
                    <p class="block">We value honesty and transparency in every project, ensuring that our clients can trust us to deliver the best results.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Call to Action Section --}}
    <section class="cta block">
        <div class="container block">
            <h2 class="cta-heading block">Want to work with us?</h2>
            <p class="cta-text block">Get in touch with us today to start building something great together.</p>
            <a href="/contact" class="btn-primary block">Contact Us</a>
        </div>
    </section>
@endsection
