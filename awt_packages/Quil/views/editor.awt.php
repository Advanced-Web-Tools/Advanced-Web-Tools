@extends('Quil.views.templates.main')
@section("scripts")
    @foreach(editor_scripts as script)
        <script type="module" src="@urlVar('script')"></script>
    @endforeach
@endsection
@section("editor")
{{ page.content }}
@endsection