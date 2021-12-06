@extends('layouts.app')

@section('content')
    @if(isset($user))
        @if($user->id == Auth::id())

            <h2 align="center">My books</h2>

            {{--Create view--}}
            <div>
                <div id="load_more">
                    <form method="get" action="{{route('edit_book')}}">
                        @csrf
                        <div class="">
                            <button type="submit" class="btn btn-primary btn-block">Create new book</button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <h2 align="center">{{$user->name}}'s books</h2>
        @endif
    @else

        <h2 align="center">Your books</h2>
    @endif

    {{--Book list view--}}

    @include('layouts.library.books-table')
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    let url = "{{ route('load_books') }}"
    var last_book_num = 0;
    var id_user = {{$user->id}};
</script>
<script type="text/javascript" src="{{ asset('js/books_loader.js') }}"></script>
