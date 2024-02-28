@extends('layouts.app')

@section('content')
    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title></title>
    <!-- Your existing head content here... -->
</head>

<body class="dark-edition">
<div class="content">
    <div class="container-fluid">
        <!-- Add the form for selecting user and files_limit -->
        <form method="post" action="{{ route('admin.set_limit') }}">
            @csrf
            <div class="form-group">
                <label for="user">Select User:</label>
                <select class="form-control" id="user" name="user_id">
                    <!-- Loop through the users and populate the dropdown -->
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="filesLimit">Enter Files Limit:</label>
                <input type="number" class="form-control" id="filesLimit" name="files_limit">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <!-- Button to go to the home page -->
        <a href="{{ route('home') }}" class="btn btn-success">Go to Home</a>

        <!-- The rest of your existing HTML content... -->

        <div class="row">
            <!-- Example of displaying user data -->
            @foreach($users as $user)
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            User: {{ $user->name }}
                        </div>
                        <div class="card-body">
                            <!-- Display other user-related data -->
                            <!-- For example: -->
                            <p>Email: {{ $user->email }}</p>
                            <p>Created at: {{ $user->created_at }}</p>
                            <!-- Add more information as needed -->
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>

<!-- Your existing scripts... -->
<script>
    const x = new Date().getFullYear();
    let date = document.getElementById('date');
    date.innerHTML = '&copy; ' + x + date.innerHTML;
</script>
</body>

</html>
@endsection
