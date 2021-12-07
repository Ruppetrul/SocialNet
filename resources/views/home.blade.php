@extends('layouts.app')

@section('content')

    @if(Auth::check())
        <h1 align="center">This all my comments</h1>

        @include('layouts.comments.comments-table')
    @else
        <h1 align="center">Only registered users can view this page...</h1>
    @endif

@endsection

@if (Auth::check())
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        var id_user = {{$user->id}};
        var last_comment_num = 0;
        let url = "{{ route('load_home_comments') }}"
    </script>
    <script type="text/javascript" src="{{ asset('js/comments_loader.js') }}"></script>
    @else
@endif

