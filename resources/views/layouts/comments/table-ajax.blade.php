@foreach($comments as $comment)
    <tr>
        <th scope="row text-capitalize">
            {{ $comment->name }}(id:{{ $comment->id_comment_author }})</th>
        <td class="text-capitalize">
            @if(isset($comment->id_comment_reply))
                <p>Reply: </p>
                <div class="container">
                    @if(isset($comment->reply_author_name)||isset($comment->reply_text)
                           ||isset($comment->reply_title))
                        <div class="modal-content modal-body mt-4 mb-4  ">
                            <p>Author: {{ $comment->reply_author_name }}</p><br>
                            <a>Title: {{ $comment->reply_title }}</a>
                            <a>{{ $comment->reply_text }}</a><br>
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
                    <form class="btn btn-primary" action="" method="GET" >
                        @csrf
                        <button class="btn btn-primary" role="button" name="reply" value="{{$comment->id_comment}}">Reply</button>
                    </form>
                        <form class="btn btn-primary" action="/profile/delete/{{ $comment->id_comment }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-block">Delete</button>
                        </form>
                @endif
            </div>
        </td>
    </tr>
@endforeach
<script>
     last_comment_num = {{$last_num}}
</script>
