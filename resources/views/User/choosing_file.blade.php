@extends('layouts.app')

@section ('content')

    <div class="container">
        <form action="{{route('history.by_file')}}" method="POST">
            @csrf
            <h1>Chose File to Show History</h1>

            <label for="email">File link:</label>
            <label>
                <select name="file_id" class="form-control" required>
                    @foreach($files as $file)
                        <option value="{{$file->id}}">{{$file->link}} </option>
                    @endforeach
                </select>
                <input type="hidden" name="file_id" value="{{ $file->id }}">
            </label>
            <button type="submit" class="btn btn-primary">View</button>
        </form>
    </div>
@endsection
