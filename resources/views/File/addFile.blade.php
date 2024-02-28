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
         <form action="{{route('files.store')}}" method="POST" enctype="multipart/form-data">
             @csrf
             <h1>Add File</h1>
             <input type="file" name="file" required>
             <input type="hidden" name="file_id" value="123">
             <div class="form-group">
                 <label for="name">Name:</label>
                 <input type="text" class="form-control" id="name" placeholder="Enter File name" name="name">
             </div>
             <div class="form-group">
                 <label for="type">Type:</label>
                 <input type="text" class="form-control" id="type" placeholder="Enter File Type" name="type">
             </div>
             <!--<div class="form-group">
               <label for="link">Link:</label>
               <input type="file" class="form-control" id="link"  name="link">
             </div>-->
             <label>
                 <select name="groups_ids[]" multiple required>
                     <!-- Populate the select options with available groups -->
                     @foreach ($groups->sortBy('created_at') as $group)
                         <option value="{{ $group->id }}">{{ $group->name }}</option>
                     @endforeach
          </select>
      </label>
      <button type="submit" class="btn btn-primary">Upload</button>
  </form>
</div>
@endsection
