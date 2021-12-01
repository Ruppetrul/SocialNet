@extends('layouts.app')

@section('content')

    @if(count($users))
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)

                        <tr onclick="window.location.href='/profile/{{$user->id}}'; return false">
                            <th scope="row">{{ $user->id }}</th>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                        </tr>

                @endforeach
                </tbody>
            </table>

        </div><!-- ./table-responsive-->
    @else
        <p>Users not found...</p>
    @endif

@endsection
