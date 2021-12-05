@extends('layouts.app')

@section('content')

    @if(isset($user))
        <div class="modal-content">
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
