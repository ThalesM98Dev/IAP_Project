@extends('layouts.app')

@section('content')
    @include('sweetalert::alert')
    <div class="container">
        <h1>File Information</h1>

        <div>
            <strong>Name:</strong> {{ $file->name }}
        </div>
        <div>
            <strong>Type:</strong> {{ $file->type }}
        </div>
        <div>
            <strong>Owner:</strong> {{ $file->owner->name }}
        </div>
        <div>
            <strong>Link:</strong> {{ $file->link }}
        </div>
        <div>
            <strong>Locked:</strong> {{ $file->locked }}
        </div>
        @if ($file->locked == 1)
            <div>
                <strong>Locked By:</strong> {{ $file->locker->name}}
            </div>
        @endif
        <!-- Additional file information or actions can be added here -->
        <form method="get" action="/files/delete/{{$file->id}}"
              onsubmit="return confirm('Are you sure you want to delete this file?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>

    </div>
@endsection

{{--<td>--}}
{{--    @if($file->owner_id == auth()->id())--}}
{{--        <a href="{{ route('files.delete_file', $file->id) }}" class="btn btn-danger">Delete</a>--}}
{{--    @endif--}}
{{--</td>--}}
