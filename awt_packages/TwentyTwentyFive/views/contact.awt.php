@extends("TwentyTwentyFive.views.template.main")

@section('head')

@endsection

@section('page')
    <div class="page block" id="page">

        <link rel="stylesheet" href="/awt_packages/TwentyTwentyFive/views/assets/css/contact.css">
        <section class="hero-contact block" style="background-image: url('/awt_packages/TwentyTwentyFive/views/assets/images/hero.svg');">
            <div class="hero-contact-content block">
                <h1 class="block">Contact Us</h1>
                <p class="block">We’d love to hear from you. Drop us a message below.</p>
            </div>
        </section>

        {{-- Contact Section --}}
        <section class="contact-section block">
            <div class="container block">
                <div class="contact-wrapper block">
                    {{-- Contact Form --}}
                    <div class="contact-form block">
                        <h2 class="block">Send a Message</h2>
                        <form method="post" action="/contact/submit" class="block">
                            <input class="block" type="text" name="name" placeholder="Your Name" required>
                            <input class="block" type="email" name="email" placeholder="Your Email" required>
                            <textarea class="block" name="message" rows="6" placeholder="Your Message" required></textarea>
                            <button type="submit" class="btn-primary block">Send Message</button>
                        </form>
                    </div>

                    {{-- Contact Info --}}
                    <div class="contact-info block">
                        <h2 class="block">Reach Us</h2>
                        <p class="block"><strong>Email:</strong> hello@example.com</p>
                        <p class="block"><strong>Phone:</strong> +1 234 567 890</p>
                        <p class="block"><strong>Address:</strong> 123 Digital St, Innovation City, 2025</p>
                        <div class="socials block">
                            <a href="#" class="block">Twitter</a>
                            <a href="#" class="block">LinkedIn</a>
                            <a href="#" class="block">Instagram</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
@endsection
