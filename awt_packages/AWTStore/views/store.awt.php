@extends("Dashboard.views.templates.main")
@section("head")
    <link rel="stylesheet" href="@asset('css/store.css')">
    <script src="https://cdn.jsdelivr.net/gh/MarketingPipeline/Markdown-Tag/markdown-tag-GitHub.js"></script>
@endsection
@section("page")
    <div class="header">
        <div class="left">
            <img src="/dashboard/store/proxy?url={{ $storeURL . $store["file_path"] }}" alt="Store image"
                 id="storeImage" crossorigin="anonymous"/>
        </div>
        <div class="right">
            <div class="top">
                <h1 class="title">{{ $store["name"] }}</h1>
            </div>
            <div class="bottom">
                @if($compatible)
                    @if($store["installed"])
                        @if($store["canUpdate"])
                            <a href="" id="update" class="btn_primary">Update available <i
                                        class="fa-solid fa-rotate"></i></a>
                        @else
                            <a id="installed" disabled class="btn_secondary">Already installed <i
                                        class="fa-regular fa-circle-check"></i></a>
                        @endif
                    @else
                        <a href="" id="install" class="install btn_primary">Install <i
                                    class="fa-solid fa-cloud-arrow-down"></i></a>
                    @endif
            </div>
            @else
                <h3>This store has no releases that are compatible with your current AWT version.</h3>
            @endif
        </div>
    </div>
    </div>
    <div class="body">
        <div class="content">
            <h2>About</h2>
            <md class="markdown-body">
                {{ $store["description"] }}
            </md>
        </div>
        <div class="side">
            <div class="info">
                <h2>Store Info</h2>
                <p>Publisher: {{ $store["user"]["username"] }}</p>
                <p>Latest version: {{ $store["packages"][0]["version"] }}</p>
                <p>AWT version compatibility: {{ $store["packages"][array_key_last($store["packages"])]["awtMin"] }} - {{ $store["packages"][0]["awtMax"] == null ? "EVERY" : $store["packages"][0]["awtMax"] }}</p>
            </div>
            <div class="packages">
                <h2>Available versions</h2>
                @if(count($store["packages"]) == 0)
                    <p>There are 0 versions that are compatible with your version of AWT.</p>
                @else
                    <div class="list">
                        @foreach($store["packages"] as $key => $package)
                            <div class="package">
                                <div class="name"><p> {{ $package['name'] }} - {{ $package['version'] }} </p></div>
                                <div class="awt"><p> {{ $package["awtMin"] }}
                                        - {{ $package["awtMax"] == null ? "EVERY" : $package["awtMax"] }}</p></div>
                                @if($package['installed'] && !$package['canUpdate'])
                                    @if(isset($package["current"]))
                                        <a href="#" class="btn_secondary">Already installed <i
                                                    class="fa-solid fa-circle-check"></i></a>
                                    @else
                                        <a href="/dashboard/store/service/install?remote_path={{ $package["file_path"] }}" class="btn_action_negative">Downgrade
                                            <i class="fa-solid fa-circle-down"></i> </a>
                                    @endif
                                @elseif($package['installed'] && $package['canUpdate'])
                                    <a href="/dashboard/store/service/install?remote_path={{ $package["file_path"] }}" class="btn_primary">Update <i class="fa-solid fa-rotate"></i></a>
                                @else
                                    <a href="/dashboard/store/service/install?remote_path={{ $package["file_path"] }}" class="btn_primary">Install <i class="fa-solid fa-cloud-arrow-down"></i></a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/colorthief@2.3.2/dist/color-thief.umd.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const img = document.getElementById("storeImage");

            img.onload = () => {
                try {
                    const colorThief = new ColorThief();

                    const dominant = colorThief.getColor(img);
                    console.log("Dom color:", dominant);

                    document.querySelector(".header").style.backgroundColor =
                        `rgb(${dominant[0]}, ${dominant[1]}, ${dominant[2]})`;

                    const palette = colorThief.getPalette(img, 6);
                    console.log("Color palette:", palette);
                } catch (e) {
                    console.error("Failed to analyze colors:", e);
                }
            };
        });
    </script>
@endsection
