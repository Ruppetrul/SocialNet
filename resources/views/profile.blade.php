@extends('layouts.app')

@section('content')

    @if(isset($data->user))

        {{--User view--}}
        <div class="modal-content mb-4">
            <div class="modal-body">
                <table>
                    <tbody>
                    <tr>Name: {{ $data->user->name }}
                        @if($data->user->id==Auth::id())
                            (You)
                        @endif
                    </tr>
                    <td>Email: {{ $data->user->email }}</td>
                    </tbody>
                </table>
            </div>
        </div>

        {{--Library view--}}
        <div class="modal-content mt-4 ">
            <div id="" class="modal-body ">

        @if(isset($data->books) || $data->user->id == Auth::id())

            <form method="get" action="/library/{{$data->user->id}}">
                @csrf
                <div class="">
                    <button type="submit" class="btn btn-primary btn-block">Go to his library</button>
                </div>
            </form>

        @elseif(Auth::check())
            <h4>The user did not give you access to their library :(</h4>
        @else
            <h4>
                Only registered users can mark the library :(</h4>
        @endif
            </div>
        </div>

        {{--Library access view--}}
        @if(Auth::id() != $data->user->id)
            <div class="mt-4 ">
                <div class="modal-content modal-body">
                    @if(isset($data->access_to_user))

                        <form action="{{ route('limit_access') }}" class=""  method="POST" >
                            @csrf
                            <button style="background: #FF0000;" class="btn btn-primary btn-block" role="button" value="{{ $data->user->id }}" name="id_user">Deny access</button>
                        </form>
                    @else
                        <form action="{{ route('allow_access') }}" class="" method="POST" >
                            @csrf
                            <button style="background: #35b606;" class="btn btn-primary btn-block" role="button" value="{{ $data->user->id }}" name="id_user">Allow access</button>
                        </form>
                    @endif
                </div>
            </div>
        @endif

        {{--Send message view--}}
        @if(Auth::check())
            <div class="modal-content modal-body mt-4 mb-4">
                @if(isset($_GET['$commented_comment']))
                    <form method="post" action="/profile/sendComment/{{ $data->user->id }}/{{ $_GET['$commented_comment'] }}">
                @else
                    <form method="post" action="/profile/sendComment/{{ $data->user->id }}">
                @endif
                    @csrf
                        <div class="col-md-12">

                        @if(isset($data->commented_comment))
                        <div class="form-group">
                            <a> Re: {{ $data->commented_comment->author_name }} </a>
                            <div class="container">
                                <div class="modal-content modal-body mt-4 mb-4  ">
                                    <p>Title: {{ $data->commented_comment->title }}</p>
                                    <a>{{ $data->commented_comment->text }}</a><br>
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

    var id_user = {{ $data->user->id }};
    var last_comment_num = 0;
    let url = "{{ route('load_profile_comments') }}"
</script>
<script type="text/javascript" src="{{ asset('js/comments_loader.js') }}"></script>
