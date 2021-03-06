@foreach($comments as $comment)
    <tr>
        <th scope="row text-capitalize">
            {{ $comment->name }}(id:{{ $comment->id_comment_author }})</th>
        <td class="text-capitalize">
            @if(isset($comment->id_comment_reply))
                <p>Reply: </p>
                <div class="container">
                    @if(isset($comment->reply))
                        <div class="modal-content modal-body mt-4 mb-4  ">
                            {{--<p>Author: {{ $comment->reply->text }}</p><br>--}}
                            <a>Title: {{ $comment->reply->title }}</a>
                            <a>{{ $comment->reply->text }}</a><br>
                        </div>
                    @else
                        <h>Comment has been deleted</h>
                    @endif
                </div>
            @endif
            <h4 class="">Title: {{ $comment->title }}</h4>
            <a>
                {{ $comment->text }}
            </a>
        </td>
    </tr>
    <tr>
        <td class="container text-capitalize" colspan="2">
            <div class="button-box col-lg-12">
                @if(Auth::check())
                    @if(!isset($isHome))
                    <form class="btn btn-primary" action="" method="GET" >
                        @csrf
                        <button class="btn btn-primary" role="button" name="reply" value="{{$comment->id_comment}}">Reply</button>
                    </form>
                    @endif
                        @if($comment->id_comment_author == Auth::id() || $id_user == Auth::id())
                        <form class="btn btn-primary" action="/profile/delete/{{ $comment->id_comment }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-block">Delete</button>
                        </form>
                    @endif
                @endif
            </div>
        </td>
    </tr>
@endforeach

