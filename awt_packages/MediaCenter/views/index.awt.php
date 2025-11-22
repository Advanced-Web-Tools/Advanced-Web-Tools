@extends('Dashboard.views.templates.main')
@section('head')
<link rel="stylesheet" href="@asset('css/widgets.css')">
<link rel="stylesheet" href="@asset('css/mediaCenter.css')">
<script type="module" src="@asset('js/MediaCenter/main.js')"></script>
@endsection
@section('topbar.widgets')
<label for="search">
</label>
<input id="search" name="search" type="text" class="inp_primary sm" placeholder="Search for media">

<form action="/dashboard/mediacenter/actions/upload" method="post" class="uploads" enctype="multipart/form-data">
    <label class="btn_secondary" for="upload">
        Select files <i class="fa-solid fa-file-import"></i>
    </label>
    <input type="file" name="upload[]" id="upload" style="display: none;" multiple>
    <button type="submit" class="btn_secondary hidden" disabled>Upload files <i class="fa-solid fa-upload"></i></button>
</form>
@endsection
@section('page')
@if(empty($mediaContent))
    <div class="empty-wrapper">
        <h1>It's little empty here....</h1>
        <img src="@asset('/images/box.png')" alt="Empty box">
    </div>
@endif
@if(!empty($mediaContent))
<div class="files">
    <div class="content_wrapper">
        @foreach($mediaContent as $media)
        <div class="wrapper" data-name="{{$media->name}}" data-index="{{$index}}" data-id="{{$media->data_id}}">
            
            @if($media->type == 'image')
                <img src="/{{$media->data->file_location}}" alt="{{$media->name}}" data-name="{{$media->name}}" data-index="{{$index}}" data-id="{{$media->data_id}}">
            @endif

            @if($media->type == 'video')
                <video controls>
                    <source src="/{{$media->data->file_location}}" type="video/mp4">
                    <source src="/{{$media->data->file_location}}" type="video/webm">
                    <source src="/{{$media->data->file_location}}" type="video/avi">
                </video>
            @endif
            @if($media->type == 'audio')
                <audio src="/{{$media->data->file_location}}"></audio>
            @endif

            @if($media->type == 'document')
                <img src="/awt_data/media/packages/image/MediaCenter/document.png" alt="{{$media->name}}" data-name="{{$media->name}}" data-index="{{$index}}" data-id="{{$media->data_id}}">
            @endif

            <div class="content-data" data-name="{{$media->name}}" data-index="{{$index}}" data-id="{{$media->data_id}}">
                @if($media->type == 'image')
                    <button data-index="{{$index}}" data-id="{{$media->data_id}}" class="btn_primary" id="enlarge" title="Enlarge image"><i
                                class="no_mrg fas fa-magnifying-glass"></i></button>
                @else
                    <a id="download" href="{{$media->data->file_location}}" download>
                        <button title="Download file." class="btn_primary"><i class="no_mrg fas fa-cloud-download"></i></button>
                    </a>
                @endif
                <p>Name: {{ $media->name }}</p>
                <p>File name: {{ $media->data->dataName }} </p>
                <p>File location: <a target="_blank" rel="nofollow" href="/{{$media->data->file_location}}">Here</a></p>
                <p>Type: {{ $media->type }}</p>
                <p>Owner: {{ $media->data->ownerName }} </p>
                <p>Owner type: {{ $media->data->ownerType }}</p>
                <div class="action">
                    @if($media->data->dataType === 'image')
<!--                        <button data-index="%index%" data-id="%content.data_id%" class="btn_secondary" title="Crop the image">Crop <i class="fas fa-crop"></i></button>-->
                    @else
                        <a target="_blank" rel="nofollow" href="/{{$media->data->file_location}}">
                            <button class="btn_secondary" title="Open the file in new tab.">Open <i class="fas fa-box-open"></i></button>
                        </a>
                    @endif
                    <a href="/dashboard/mediacenter/actions/delete/{{$media->data_id}}" class="btn_action_negative"
                       title="Delete"><i class="fas fa-trash-can no_mrg"></i></a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
<div class="helper">
    <div class="content"></div>
</div>

<div class="page-sidebar">
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const fileInput = document.getElementById("upload");
        const label = document.querySelector("label[for='upload']");
        const submitBtn = document.querySelector(".uploads button[type='submit']");

        fileInput.addEventListener("change", function () {
            const count = fileInput.files.length;

            if (count > 0) {
                label.textContent = count + " file(s) selected";
                label.classList.add("selected");
                submitBtn.classList.remove("hidden");
                submitBtn.disabled = false;
            } else {
                label.innerHTML = 'Select files <i class="fa-solid fa-file-import"></i>';
                submitBtn.classList.add("hidden");
                submitBtn.disabled = true;
            }
        });
    });
</script>


@endsection
