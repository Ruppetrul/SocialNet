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
    @endif
    @if(count($comments))
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th scope="col">Author</th>
                    <th scope="col">Comment</th>
                </tr>
                </thead>

                <tbody>
                @foreach($comments as $comment)
                    <tr>
                        <th scope="row">{{ $comment->id }}</th>
                        <td>{{ $comment->text }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div><!-- ./table-responsive-->
    @else
        <p>Комментариев не найдено...</p>
    @endif
@endsection
