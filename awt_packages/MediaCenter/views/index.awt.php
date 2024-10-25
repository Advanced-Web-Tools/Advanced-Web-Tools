@extends('Dashboard.views.templates.main')
@section('head')
<link rel="stylesheet" href="@assets('css/widgets.css')">
<link rel="stylesheet" href="@assets('css/mediaCenter.css')">
<script type="module" src="@assets('js/MediaCenter/main.js')"></script>
@endsection
@section('topbar.widgets')
<label for="search">
</label>
<input id="search" name="search" type="text" class="inp_primary sm" placeholder="Search for media">
<form action="/dashboard/media/action/upload" method="post" class="uploads" enctype="multipart/form-data">
    <label class="btn_secondary" for="upload">
        Select files <i class="fa-solid fa-file-import"></i>
    </label>
    <input type="file" name="upload[]" id="upload" style="display: none;">
    <button type="submit" class="btn_secondary">Upload files <i class="fa-solid fa-upload"></i></button>
</form>
@endsection
@section('page')
<h1 class="title">{{ title }}</h1>
<div class="files">
    <div class="content_wrapper">
        @foreach(media as content)
        <div class="wrapper" data-name="%content.name%" data-index="%index%" data-id="%content.data_id%">
            <img src="%content.data.location%" alt="%content.name%" data-name="%content.name%" data-index="%index%" data-id="%content.data_id%">
            <div class="content-data" data-name="%content.name%" data-index="%index%" data-id="%content.data_id%">
                @if(content.data.sDataType === 'image')
                    <button data-index="%index%" data-id="%content.data_id%" class="btn_primary" id="enlarge" title="Enlarge image"><i
                                class="no_mrg fas fa-magnifying-glass"></i></button>
                @endif
                <p>Name: {{ content.name }}</p>
                <p>File name: {{ content.data.fileName }} </p>
                <p>File location: <a target="_blank" rel="nofollow" href="@urlVar('content.data.location')">Here</a></p>
                <p>Owner: {{ content.data.owner }} </p>
                <p>Owner type: {{ content.data.sOwnerType }}</p>
                <div class="action">
                    @if(content.data.sDataType === 'image')
                        <button data-index="%index%" data-id="%content.data_id%" class="btn_secondary" title="Crop the image">Crop <i class="fas fa-crop"></i></button>
                    @endif
                    <a href="@url('dashboard/mediacenter/actions/delete/%content.data_id%')" class="btn_action_negative"
                       title="Delete"><i class="fas fa-trash-can no_mrg"></i></a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<div class="helper">
    <div class="content"></div>
</div>

<div class="page-sidebar">
</div>
@endsection
