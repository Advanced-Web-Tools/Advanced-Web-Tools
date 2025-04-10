@extends('Dashboard.views.templates.main')

@section('head')
<link rel="stylesheet" href="@asset('css/themes.css')">
@endsection

@section("page")
<div class="themes">
    <div class="title">
        <h3>Installed themes</h3>
    </div>
    @foreach($themes as $theme)
        @if($theme->package->previewImage !== null)
        <div class="theme" style="background-image:url('{{$theme->package->previewImage}}');">
        @else
        <div class="theme" style="background-image:url('@asset('images/6155fdafbe9057a007832d90_wireframing-101_bloghero.jpg')');">
        @endif
            <div class="info">
                <p> {{ $theme->package->name }} </p>
                <p> {{ $theme->package->description }} </p>
                <div class="action">
                    @if($theme->status != 1)
                    <a class="hp_primary" href="">
                        <button class="btn_primary">Change theme</button>
                    </a>
                    @else
                        <button class="btn_primary" disabled>Current theme</button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
<div class="theme-settings">
    <div class="title">
        <h3>{{$theme->package->name}} settings</h3>
    </div>
    <div class="settings">
        @foreach($settings as $setting)
        <form class="setting">
            <label for="{{$setting["name"]}}">
                {{$setting["name"]}}
            </label>
            <input class="inp_primary md" id="{{$setting["name"]}}" name="{{$setting["name"]}}" value="{{$setting["value"]}}" type="{{$setting["type"]}}" />
            <button class="btn_secondary">Apply</button>
        </form>
        @endforeach
    </div>
</div>
<div class="theme-pages">
    <div class="title">
        <h3>{{$theme->package->name}} pages</h3>
    </div>
    <div class="pages">
        @foreach($pages as $page)
        <div class="theme-page">
                <p>{{$page['name']}}</p>
                <p>Path: {{$page['route']}}</p>
                <a href="{{$page['route']}}" target="_blank" rel="nofollow">Visit</a>
                <a href="/theming/customize/{{$page['name']}}/{{$theme->id}}" class="hp_primary">
                    <button class="btn_secondary">Customize</button>
                </a>
        </div>
        @endforeach
    </div>
</div>
@endsection