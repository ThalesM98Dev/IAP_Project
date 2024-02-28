@extends('layouts.app')

@section ('content')

    <div class="container">
        <form action="{{route('groups.add_user')}}" method="POST">
            @csrf
            <h1>Add Member</h1>
            <input type="hidden" name="group_id" value="{{ $group_id }}">
            <label for="email">User name:</label>
            <label>
                <select name="user_id" class="form-control" required>
                    @foreach($users as $user)
                        <option value="{{$user->id}}">{{$user->name}} </option>
                    @endforeach
                </select>
            </label>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
