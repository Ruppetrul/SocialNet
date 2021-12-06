@foreach($books as $book)
    <li class="list-group-item">
        <div class="container ">
            <a class="m-2">{{$book->name}}</a> <br>
            <i class="m-2">Created_at: {{$book->created_at}}</i> <br>
            <i class="m-2">Last_updated_at: {{$book->updated_at}}</i>
            <br>
            @if($book->id_author == Auth::id())
                <form class="btn btn-primary" action="/library/edit_book/{{$book->id}}" method="GET" >
                    @csrf
                    <button class="btn btn-primary" role="button" name="reply">Edit</button>
                </form>
                <form class="btn btn-primary" action="{{route('delete_book')}}" method="POST">
                    @csrf
                    <button type="submit" name="book_id" value="{{$book->id}}" class="btn btn-primary">Delete book</button>
                </form>
                <form class="btn btn-primary" action="{{route('share_book')}}" method="POST">
                    @csrf
                    <button type="submit" name="book_id" value="{{$book->id}}" class="btn btn-primary">Share</button>
                </form>
            @endif

            <br>
            <form class="btn btn-primary" action="/library/read_book/{{$book->id}}" method="GET">
                @csrf
                <button type="submit" name="book_id" value="{{$book->id}}" class="btn btn-primary">Read</button>
            </form>
        </div>
    </li>
@endforeach

