@extends('Dashboard.views.templates.main')
@section("page")
    <div class="menus">
        @foreach($menus as $menu)
            <div class="menu">
                <p class="menu-name">{{ $menu["name"] }}</p>
            </div>
        @endforeach
    </div>
    <div class="items">
        @foreach($menu_items as $item)
            <div class="item" data-id="{{ $item['menu_id'] }}">
                <p>Item ID: {{ $item["id"] }}</p>
                <label for="name_{{ $item['id'] }}">Text:</label>
                <input id="name_{{ $item['id'] }}" type="text" class="inp_primary name" value="{{ $item['name'] }}">
                <label for="link_{{ $item['id'] }}">Location:</label>
                <input id="link_{{ $item['id'] }}" type="text" class="inp_primary link" value="Link">
                <label for="target_{{ $item['id'] }}">
                    Target:
                </label>
                <select name="target" id="target_{{ $item['id'] }}" class="select_primary">
                    <option value="{{ $item['target'] }}">{{ $item['target'] }}</option>
                    <option value="_self">Current</option>
                    <option value="_blank">New Tab</option>
                </select>
                <select name="" id="parent_{{ $item['id'] }}">
                    <option value="0">None</option>
                    @foreach($menu_items as $parent)
                        <option value="{{ $parent['id'] }}">{{ $parent['id'] }}</option>
                    @endforeach
                </select>
            </div>
        @endforeach
    </div>
@endsection