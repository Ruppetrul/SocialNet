@extends('layouts.app')

@section('content')
{{--Edit form--}}
@if(isset($book) and isset($isRead))

    {{--Read form--}}
    <div>
        <div id="load_more">
            <form method="post" action="">
                @csrf
                <div class="form-group">
                    <input readonly type="text" class="form-control" id="name" name="name" minlength=2 placeholder="Book name"
                        value="{{$book->name}}">
                </div>
                <div class="form-group">
                    <textarea disabled=disabled type="text" size="20" style="min-height: 180px;" class="form-control" id="text" minlength=3
                              name="text" placeholder="Text">{{$book->text}}</textarea>
                </div>
            </form>
        </div>
    </div>

@elseif(isset($book))
    {{--Edit form--}}
    <div>
        <div id="load_more">
            <form method="post" action="/library/alter_book/{{$book->id}}">
                @csrf
                <div class="form-group">
                    <input type="text" class="form-control" id="name" name="name" minlength=2 placeholder="Book name"
                           value="{{$book->name}}">
                </div>
                <div class="form-group">
                    <textarea type="text" size="20" style="min-height: 180px;" class="form-control" id="text" minlength=3
                              name="text" placeholder="Text">{{$book->text}}</textarea>
                </div>
                <div class="">
                    <button type="submit" class="btn btn-primary btn-block">Save book</button>
                </div>
            </form>
        </div>
    </div>
@else
    {{--Create form--}}
    <div>
        <div id="load_more">
            <form method="post" action="{{route('add_book')}}">
                @csrf
                <div class="form-group">
                    <input type="text" class="form-control" id="name" name="name" minlength=2 placeholder="Book name"
                           value="">
                </div>
                <div class="form-group">
                    <textarea type="text" size="20" style="min-height: 180px;" class="form-control" id="text" minlength=3
                              name="text" placeholder="Text"></textarea>
                </div>
                <div class="">
                    <button type="submit" class="btn btn-primary btn-block">Save book</button>
                </div>
            </form>
        </div>
    </div>
@endif


@endsection
