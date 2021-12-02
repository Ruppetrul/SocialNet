@extends('layouts.app')

@section('content')

    @if(isset($user))
        <div class="modal-content">
            <div class="modal-body">

                <table>
                    <tbody>
                    <tr>Name: {{ $user['name'] }}
                        @if($user['id']==Auth::id())
                            (You)
                        @endif

                    </tr>
                    <td>Email: {{ $user['email'] }}</td>
                    </tbody>
                </table>

            </div>
        </div>

        @if(Auth::check())
            <div class="modal-content modal-body mt-4 mb-4">
                @if(isset($_GET['reply']))
                    <form method="post" action="/profile/sendComment/{{ $user['id'] }}/{{ $_GET['reply'] }}">
                @else
                    <form method="post" action="/profile/sendComment/{{ $user['id'] }}">
                @endif
                    @csrf
                         <div class="col-md-12">

                         @if(isset($_GET['reply']))
                         <div class="form-group">
                             <a> Reply to comment on id: {{$_GET['reply']}} </a>
                         </div>
                         @endif

                         <div class="form-group">
                             <input type="text" class="form-control" id="title" name="title" required minlength=6 maxlength="36" placeholder="Title">
                         </div>
                         <div class="form-group">
                             <input type="text" required class="form-control" id="text" minlength=3 maxlength="255" name="text" placeholder="Text">
                         </div>
                         <div class="">
                             <button type="submit" class="btn btn-primary btn-block">Send</button>
                         </div>
                            </div>
                        </form>

            </div>
        @else
            <div class="modal-content modal-body mt-4 mb-4 ">
                <div class="text-danger text-center">
                    <a>Unregistered users can not leave comments. Login or register.</a>
                </div>
            </div>


        @endif

        @if(count($comments))
            <div class="table-responsive">
                <table class="table table-hover table-striped">

                    <tbody>Сообщение от
                    @foreach($comments as $comment)
                        <tr>
                            <th scope="row">{{ $comment->name }}(id:{{ $comment->id_comment_author }})</th>
                            <td class="container text-capitalize">
                                @if(isset($comment->id_comment_reply))
                                    <p>Reply:</p>

                                    <div class="container">
                                        <div class="modal-content modal-body mt-4 mb-4  ">
                                            <a>Author: {{ $comment->reply_author_name }}</a><br>
                                            <a>{{ $comment->reply_text }}</a><br>
                                        </div>
                                    </div>
                                @endif
                                <p>
                                   {{-- {{ $comment->title }}--}}
                                    {{--Title--}}
                                </p>
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
                                            <button class="btn btn-primary" role="button" name="reply" value="{{$comment->id}}">Reply</button>
                                        </form>
                                            @if($comment->id_comment_author == Auth::id() || $user->id == Auth::id())
                                            <form class="btn btn-primary" action="/profile/delete/{{ $comment->id }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-block">Delete</button>
                                            </form>
                                            @endif
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>



                    @endforeach
                    </tbody>

                </table>
            </div><!-- ./table-responsive-->
        @else
            <p>Комментариев не найдено...</p>
        @endif
    @endif
@endsection
