@extends('layouts.app')

@section('content')

    @if(isset($user))

        {{--User view--}}
        <div class="modal-content mb-4">
            <div class="modal-body">
                <table>
                    <tbody>
                    <tr>Name: {{ $user->name }}
                        @if($user->id==Auth::id())
                            (You)
                        @endif
                    </tr>
                    <td>Email: {{ $user->email }}</td>
                    </tbody>
                </table>
            </div>
        </div>

        {{--Library view--}}
        <div class="modal-content mt-4 ">
            <div id="" class="modal-body ">

        @if(isset($books) || $user->id == Auth::id())

            <form method="get" action="/library/{{$user->id}}">
                @csrf
                <div class="">
                    <button type="submit" class="btn btn-primary btn-block">Go to his library</button>
                </div>
            </form>

        @else
            <h4>The user did not give you access to their library :(</h4>
        @endif

            </div>
        </div>

        {{--Library access view--}}

        @if(Auth::id() != $user->id)

            <div class="mt-4 ">
                <div class="modal-content modal-body">
                    @if(isset($access_to_user))

                        <form action="{{ route('limit_access') }}" class=""  method="POST" >
                            @csrf
                            <button style="background: #FF0000;" class="btn btn-primary btn-block" role="button" value="{{ $user->id }}" name="id_user">Запретить пользователю доступ</button>
                        </form>
                    @else
                        <form action="{{ route('allow_access') }}" class="" method="POST" >
                            @csrf
                            <button style="background: #35b606;" class="btn btn-primary btn-block" role="button" value="{{ $user->id }}" name="id_user">Разрешить пользователю доступ</button>
                        </form>
                    @endif
                </div>

            </div>
        @endif



        {{--Send message view--}}
        @if(Auth::check())
            <div class="modal-content modal-body mt-4 mb-4">
                @if(isset($_GET['reply']))
                    <form method="post" action="/profile/sendComment/{{ $user['id'] }}/{{ $_GET['reply'] }}">
                @else
                    <form method="post" action="/profile/sendComment/{{ $user['id'] }}">
                @endif
                    @csrf
                        <div class="col-md-12">

                        @if(isset($reply))
                        <div class="form-group">
                            <a> Re: {{ $reply->author_name }} </a>
                            <div class="container">
                                <div class="modal-content modal-body mt-4 mb-4  ">
                                    <p>Title: {{ $reply->title }}</p>
                                    <a>{{ $reply->text }}</a><br>
                                </div>
                            </div>
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

        @include('layouts.comments.comments-table')

        @else
            <p>Комментариев не найдено...</p>
        @endif

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    var user_id = {{ $user->id }};
    var last_comment_num = 0;
    let url = "{{ route('load_profile_comments') }}"
</script>
<script type="text/javascript" src="{{ asset('js/comments_loader.js') }}"></script>
