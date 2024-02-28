<!-- resources/views/search/searchbarfiles.blade.php -->

<form action="{{route('search.for_files')}}" method="post">
    @csrf
    <label>
        <input type="text" name="search_text" placeholder="Search for a file...">
    </label>
    <button type="submit" class="btn btn-default btn-round btn-just-icon">
        <i class="material-icons">search</i>
    </button>
</form>

{{--<button type="submit" class="btn btn-default btn-round btn-just-icon">--}}
{{--    <i class="material-icons">search</i>--}}
{{--    <div class="ripple-container"></div>--}}
{{--</button>--}}
