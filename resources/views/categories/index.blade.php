
@extends('layout.main')

@section('content')
<script src="https://kit.fontawesome.com/1b48e60650.js" crossorigin="anonymous"></script>

<ul class="notifications"></ul>

<link rel="stylesheet" href="{{ asset('assets/css/alert.css') }}">
<script src="{{ asset('assets/js/alert.js') }}"></script>
@if(session()->has('success'))
    <input type="hidden" id="myElement" message="{{ session('success') }}">
    <script>
        var element = document.getElementById('myElement');
        var message = element.getAttribute('message');
        createToast('success', message);
    </script>
@endif

<div class="content-wrapper">
  <div class="p-4" style="background-color: #191c24;border-radius:0.5rem">
    <div class="d-flex justify-content-between mb-3">
      <h3 class="my-auto">Categories List</h3>
      <a class="btn btn-outline-primary" href="{{ route('categories.create') }}" style="font-size:1rem;font-weight:500;align-items:center"><i class="fa-solid fa-plus"></i> Add Category</a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="text-center" style="font-weight:600;"> No </th>
              <th class="text-center" style="font-weight:600;"> Category Name </th>
              <th class="text-center" style="font-weight:600;"> Total Product </th>
              <th class="text-center" style="font-weight:600;"> Action </th>
            </tr>
          </thead>
            <tbody>
                @forelse ($category as $item)
                    <tr>
                        <td class="text-center"> {{ $category->firstItem() + $loop->index }} </td>
                        <td> {{ $item->name }} </td>
                        <td class="text-center"> {{ count($item->products) }} </td>
                        <td class="justify-content-center d-flex">
                            <a href="{{ route('categories.edit', $item->id) }}" class="btn btn-outline-warning" style="margin-right: 0.5rem;font-size:1rem"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                            <form action="{{ route('categories.destroy', $item->id) }}" method="post">
                                @method('delete')
                                @csrf
                                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('What are you sure? ..')" style="font-size:1rem"><i class="fa-solid fa-trash"></i> Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr class="text-center">
                        <td colspan="7" class="alert text-center">Category Not Available !!</td>
                    </tr>
                @endforelse
          </tbody>
        </table>
        <div class="d-flex justify-content-end mt-3">
            {{ $category->links('components.pagination') }}
        </div>
    </div>
  </div>
</div>
@endsection
