@extends('layouts.app')

@section ('content')

    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{route('files.checkout_file')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <h1>Change File</h1>
            <!--<input type="file" name="file" required>-->
            <input type="hidden" name="file_id" value="{{ $file->id }}">
            <div class="form-group">
                <label for="file">File:</label>
                <input type="file"
                       class="form-control"
                       id="file"
                       placeholder="Enter image"
                       name="file"
                       value="{{$file->file}}">
            </div>
            <button type="submit" class="btn btn-primary">Upload New File</button>
        </form>
    </div>
@endsection
