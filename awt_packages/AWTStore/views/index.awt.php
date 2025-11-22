@extends("Dashboard.views.templates.main")
@section("head")
    <link rel="stylesheet" href="@asset('css/index.css')">
    <script src="https://cdn.jsdelivr.net/gh/MarketingPipeline/Markdown-Tag/markdown-tag-GitHub.js"></script>
@endsection
@section("page")
    <div class="top shadow">
        <div class="left">
            <h1>Marketplace</h1>
        </div>
        <div class="right">

        </div>
    </div>
    <div class="stores">
        @foreach($stores as $name => $store)
            <a class="store shadow hp_primary" href="/dashboard/store/view?uid={{ $store["uid"] }}">
                <div class="header">
                    <img src="{{ $storeURL . $store["file_path"] }}" alt="Icon">
                </div>
                <div class="content">
                    <h3 class="title">{{ $name }}</h3>
                    <p class="by">By: {{ $store["user"]["username"] }}</p>
                    <md class="description">
                        {{ substr($store["description"], 0, 200) }}...
                    </md>
                </div>
                <div class="action">
                    @if($store["installed"])
                        @if($store["canUpdate"])
                            <button id="update" class="btn_secondary">Update available <i class="fa-regular fa-circle-down"></i></button>
                        @else
                            <button id="installed" disabled class="btn_secondary">Already installed <i class="fa-regular fa-circle-check"></i></button>
                        @endif
                    @else
                        <button id="install" class="install btn_primary">Download <i class="fa-solid fa-cloud-arrow-down"></i> </button>
                    @endif
                </div>
            </a>

        @endforeach
    </div>
@endsection