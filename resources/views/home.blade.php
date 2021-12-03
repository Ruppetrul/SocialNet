@extends('layouts.app')

@section('content')

    @if(count($comments))
        <h1 align="center">This all my comments</h1>
        <div class="table-responsive">
            <table class="table table-hover table-striped">

                <tbody>
                @foreach($comments as $comment)
                    <tr>

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
                                        <p>Comment has been deleted</p>
                                    @endif

                                </div>
                            @endif
                            <p>
                            {{-- {{ $comment->title }}--}}
                            <h4 class="">Title: {{ htmlspecialchars($comment->title) }}</h4>
                            </p>
                            <a>
                                {{ htmlspecialchars($comment->text) }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td class="container text-capitalize" colspan="2">
                            <div class="button-box col-lg-12">
                                @if(Auth::check())
                                    <form class="btn btn-primary" action="" method="GET" >
                                        @csrf
                                        <button class="btn btn-primary" role="button" name="reply" value="{{ htmlspecialchars($comment->id_comment) }}">Reply</button>
                                    </form>
                                    @if($comment->id_comment_author == Auth::id() || $user->id == Auth::id())
                                        <form class="btn btn-primary" action="/profile/delete/{{ htmlspecialchars($comment->id_comment) }}" method="POST">
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
        <h1 align="center">Only an authorized user can leave comments</h1>
    @endif

@endsection
