@extends('layouts.app')

@section ('content')

    <div class="container">
        <form action="{{route('groups.add_group')}}" method="POST">
            @csrf
            <h1>Create Group</h1>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" placeholder="Enter group name" name="name">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
