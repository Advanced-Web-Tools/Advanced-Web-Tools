@extends('Dashboard.views.templates.main')
@section("head")
    <link rel="stylesheet" href="@asset('css/index.css')">
    <script src="@asset('js/DismisBanner.js')"></script>
@endsection
@section('page')
    <div class="welcome-banner">
        <div class="dismiss-wrapper"><button class="dismiss-btn" onclick="dismissBanner()">×</button></div>
        <div class="banner-content">
            <h2>Welcome to Advanced Web Tools - CMS</h2>
            <p>Your journey in managing your website starts here! Choose your next step:</p>
            <div class="links">
                <a href="https://github.com/Advanced-Web-Tools/Advanced-Web-Tools" target="_blank">Star a Project</a>
                <a href="https://github.com/Advanced-Web-Tools/Advanced-Web-Tools/wiki" target="_blank">Visit Wiki</a>
                <a href="/dashboard/pages" target="_blank">Create Your First Page</a>
                <a href="/dashboard/accounts/" target="_blank">Add another account</a>
                <a href="/dashboard/settings/" target="_blank">Change settings</a>
                <a href="/dashboard/media" target="_blank">Upload your media</a>
                <a href="/dashboard/themes" target="_blank">Customize your site</a>
            </div>
        </div>
    </div>
    <div class="widgets">
{{--        <div class="widget">--}}
{{--            <div class="header">--}}
{{--                <h4>Quick Nav</h4>--}}
{{--                <button data-name="quick-nav" class="dismiss-btn" onclick="collapseWidget()">×</button>--}}
{{--            </div>--}}
{{--            <div class="content">--}}
{{--                @foreach($paths as $path)--}}
{{--                    <p>{{ $path }}</p>--}}
{{--                @endforeach--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>
@endsection
