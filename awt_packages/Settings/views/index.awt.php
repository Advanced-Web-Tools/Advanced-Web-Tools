@extends('Dashboard.views.templates.main')
@section('head')
    <link rel="stylesheet" href="@asset('css/settings.css')">
@endsection
@section('page')
    <div class="categories">
        @foreach($cats as $cat)
            @if($cat == $category)
                <a href="./{{$cat}}" class="category current">{{ $cat }} </a>
            @else
                <a href="./{{$cat}}" class="category">{{ $cat }} </a>
            @endif
        @endforeach
    </div>
    <div class="settings">
        @foreach($settings as $setting)
            <form class="setting-container" data-category="{{ $setting->category }}" action='/settings/change/'
                  method="post">
                <label for="setting">{{ $setting->name }}: </label>
                @if($setting->value_type == 'text')
                    <input type="text" name="{{ $setting->name }}" id="setting" class="inp_primary lg"
                           value="{{ $setting->value }}">
                @endif
                @if($setting->value_type == 'number')
                    <input type="number" name="{{ $setting->name }}" id="setting" class="inp_primary lg"
                           value="{{ $setting->value }}">
                @endif
                @if($setting->value_type == 'boolean')
                    @if($setting->value == 'true')
                        <input type="checkbox" id="{{ $setting->name }}" name="{{ $setting->name }}" id="setting"
                               checked="true">
                    @else
                        <input type="checkbox" id="{{ $setting->name }}" name="{{ $setting->name }}" id="setting">
                    @endif
                @endif
                <button class="btn_primary" type="submit" name="change" value="{{ $setting->name }}">Save</button>
            </form>
        @endforeach
    </div>
@endsection