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
                <form method="post" action="/profile/sendComment/{{ $user['id'] }}">
                    @csrf
                    <div class="col-md-12">
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

                    <tbody>
                    @foreach($comments as $comment)
                        <tr>
                            <th scope="row">{{ $comment->id_comment_author }}</th>
                            <td>{{ $comment->text }}</td>
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
