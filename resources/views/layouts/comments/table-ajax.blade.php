@foreach($comments as $comment)
    <tr>
        <th scope="row text-capitalize">
            {{ htmlspecialchars($comment->name) }}(id:{{ htmlspecialchars($comment->id_comment_author) }})</th>
        <td class="text-capitalize">
            @if(isset($comment->id_comment_reply))
                <p>Reply: </p>
                <div class="container">

                    @if(isset($comment->reply_author_name)||isset($comment->reply_text)
                           ||isset($comment->reply_title))

                        <div class="modal-content modal-body mt-4 mb-4  ">
                            <p>Author: {{ htmlspecialchars($comment->reply_author_name) }}</p><br>
                            <a>Title: {{ htmlspecialchars($comment->reply_title) }}</a>
                            <a>{{ htmlspecialchars($comment->reply_text) }}</a><br>
                        </div>

                    @else
                        <h>Comment has been deleted</h>
                    @endif

                </div>
            @endif
            {{-- {{ $comment->title }}--}}
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
                    @if($comment->id_comment_author == Auth::id() || $user->id == Auth::id())
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
<script>
    var d = document.getElementById("load_more_button");  //   Javascript
    d.setAttribute('num' , {{$comments->last()->num}});
    d.textContent = {{$comments->last()->num}};
</script>
