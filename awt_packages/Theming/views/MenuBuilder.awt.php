@extends('Dashboard.views.templates.main')
@section('head')
    <link rel="stylesheet" href="@asset('css/menuBuilder.css')">
    <script type="module" src="@asset('js/MenuBuilder.js')"></script>
@endsection
@section("page")
    <div class="menus shadow">
        @foreach($menus as $menu)
            <div class="menu">
                <p class="menu-name">{{ $menu["name"] }}</p>
                @if($menu["active"] == 1)
                    <input type="checkbox" class="menu_select" data-id="{{ $menu["id"]  }}" checked>
                @else
                    <input type="checkbox" class="menu_select" data-id="{{ $menu["id"]  }}">
                @endif
            </div>
        @endforeach
    </div>
    <div class="items">
        @foreach($menu_items as $item)
            <div class="item hidden" data-menu-id="{{ $item['menu_id']  }}" data-id="{{ $item['id'] }}">
                <p class="identifier">Item ID: {{ $item["id"] }}</p>
                <label for="name_{{ $item['id'] }}">Text:</label>
                <input id="name_{{ $item['id'] }}" type="text" class="inp_primary name" value="{{ $item['name'] }}">
                <label for="link_{{ $item['id'] }}">Location:</label>
                <input id="link_{{ $item['id'] }}" type="text" class="inp_primary link" value="{{ $item['link']  }}">
                <label for="target_{{ $item['id'] }}">
                    Target:
                </label>
                <select name="target" id="target_{{ $item['id'] }}" class="select_primary" style="width: 100px;">
                    @if($item['target'] !== null)
                        <option value="{{ $item['target'] }}">{{ $item['target'] }}</option>
                    @else
                        <option value="null">Select target</option>
                    @endif
                    <option value="_self">Current</option>
                    <option value="_blank">New Tab</option>
                </select>
                <label for="parent_{{ $item['id'] }}">Parent:</label>
                <select name="parent" id="parent_{{ $item['id'] }}" class="select_primary" style="width: 100px">
                    @if($item['parent_item'] !== null)
                        <option value="{{ $item['parent_item'] }}">{{ $item['parent_item'] }}</option>
                        <option value="null">Select parent item</option>
                    @else
                        <option value="null">Select parent item</option>
                    @endif
                    @foreach($menu_items as $parent)
                        @if($parent["menu_id"] == $item["menu_id"])
                            <option value="{{ $parent['id'] }}">{{ $parent['id'] }}</option>
                        @endif
                    @endforeach
                </select>
                <button data-id="{{ $item['id']  }}" class="btn_primary save_item"><i
                            class="no_mrg fa-solid fa-save"></i></button>
                <button data-id="{{ $item['id']  }}" class="btn_action_negative delete_item"><i
                            class="no_mrg fa-solid fa-trash-can"></i></button>
            </div>
        @endforeach
    </div>
    <div class="action">
        <button id="addItemButton" class="btn_primary">Add New Item</button>
    </div>
@endsection