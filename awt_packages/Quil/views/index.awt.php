@extends('Dashboard.views.templates.main')
@section('head')
<link rel="stylesheet" href="@assets('css/pages.css')">
<script type="module" src="@assets('js/manage/main.js')"></script>
@endsection
@section('topbar.widgets')
<form action="/quil/create" method="post" class="page_create">
    <label for="create_page">

    </label>
    <input type="text" name="page_name" id="create_page" class="inp_primary" placeholder="Page name">
    <button type="submit" class="btn_secondary">Create page <i class="fa-regular fa-square-plus"></i></button>
</form>
@endsection
@section('page')
<table class="list">
    <thead>
    <tr>
        <th colspan="5" class="title">Page List</th>
    </tr>
    <tr class="header">
        <th class="name">Name</th>
        <th>Creation date</th>
        <th>Last updated</th>
        <th>Author</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    @foreach(pages as page)
    <tr class="info">
        <td class="name">{{ page.name }}</td>
        <td>{{ page.creation_date }}</td>
        <td>{{ page.last_update }}</td>
        <td>{{ page.admin.username }}</td>
        <td class="actions">
            <a href="/quil/page_editor/%page.id%?id=%page.id%" target="_blank" rel="nofollow">
                <button class="btn_primary">Edit <i class="fa-solid fa-feather"></i></button>
            </a>
            <button class="manage_page btn_secondary" data-id="%page.id%">Manage <i class="fa-solid fa-gear"></i></button>
            <a href="/quil/delete/%page.id%">
                <button class="btn_action_negative">Delete <i class="fa-solid fa-trash"></i></button>
            </a>
        </td>
    </tr>
    @endforeach
    @if(pages == null)
    <tr class="info">
        <td class="name" colspan="6">Create new page to populate this list.</td>
    </tr>
    @endif
    </tbody>
    <tfoot>
    <tr class="header">
        <th class="name">Name</th>
        <th>Creation date</th>
        <th>Last updated</th>
        <th>Author</th>
        <th>Actions</th>
    </tr>
    </tfoot>
</table>
<button type="button" id="createRoute" class="btn_secondary">Create <i class="fa-regular fa-square-plus"></i></button>
<table class="list">
    <thead>
    <tr>
        <th colspan="5" class="title">Routes</th>
    </tr>
    <tr class="header">
        <th class="name">Path</th>
        <th>Creation Date</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    @foreach(custom_routes as route)
    <tr class="info">
        <td class="name">{{ route.route }}</td>
        <td>{{ route.creation_date }}</td>
        <td class="actions">
            <a href="/quil/route_delete/%route.id%">
                <button class="btn_action_negative">Delete <i class="fa-solid fa-trash"></i></button>
            </a>
        </td>
    </tr>
    @endforeach
    @if(custom_routes == null)
    <tr class="info">
        <td class="name" colspan="6">Create new route or page to populate this list.</td>
    </tr>
    @endif
    </tbody>
    <tfoot>
    <tr class="header">
        <th class="name">Path</th>
        <th>Creation Date</th>
        <th>Actions</th>
    </tr>
    </tfoot>
</table>


<div class="helper">
    <div class="content"></div>
</div>

@endsection