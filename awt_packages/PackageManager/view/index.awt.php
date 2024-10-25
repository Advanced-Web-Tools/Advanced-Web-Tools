@extends('Dashboard.views.templates.main')
@section("head")
<link rel="stylesheet" href="@assets('css/packages.css')">
@endsection
@section("topbar.widgets")
<form action=/package_manager/installer/install" enctype="multipart/form-data" method="post">
    <input type="file" name="package" id="packageUpload">
    <button type="submit" class="btn_secondary">Install package.</button>
</form>
@endsection
@section("page")
<div class="package_list">
    <table class="package_table shadow">
        <thead>
        <tr>
            <th colspan="7">{{filter}} Package List</th>
        </tr>
        <tr>
            <th>Icon</th>
            <th>Name</th>
            <th>Description</th>
            <th>Publisher</th>
            <th>System Package</th>
            <th>Version</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach(packages as package)
        <tr>
            <td>
                <img src="@urlVar('package.icon')" alt="N/A" class="package_icon">
            </td>
            <td class="name">{{package.name}}</td>
            <td class="description">{{package.description}}</td>
            <td class="author">{{package.author}}</td>
            @if(package.system == '1')
                <td class="system">True</td>
            @else
                <td class="system">False</td>
            @endif
            <td class="version">{{ package.version }}</td>
            <td class="actions">
                <button class="btn_primary">Info</button>
                @if(package.type !== "System")
                <a href="/package_manager/installer/uninstall/%package.id%">
                    <button class="btn_action_negative">Uninstall <i class="fas fa-trash-can"></i> </button>
                </a>
                    @if(package.status === "Active")
                        <a href="/package_manager/actions/disable/%package.id%/">
                            <button class="btn_action_negative">Disable</button>
                        </a>
                    @else
                        <a href="/package_manager/actions/enable/%package.id%/">
                            <button class="btn_secondary">Enable</button>
                        </a>
                        @endif
                @endif
            </td>
        </tr>
        @endforeach
        @if(packages == null)
            <tr>
                <td colspan="7">Install some <b>{{ filter }}</b> packages to populate this list.</td>
            </tr>
        @endif
        </tbody>
        <tfoot>
        <tr>
            <th>Icon</th>
            <th>Name</th>
            <th>Description</th>
            <th>Publisher</th>
            <th>System Package</th>
            <th>Version</th>
            <th>Action</th>
        </tr>
        </tfoot>
    </table>
</div>
@endsection