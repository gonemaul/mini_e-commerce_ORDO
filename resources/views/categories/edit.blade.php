@extends('layout.main')

@section('content')
<div class="content-wrapper">
    <div class="p-4" style="background-color: #191c24;border-radius:0.5rem">
        <div class="title mb-5">
            <h4>Edit Category</h4>
        </div>
        <form action="{{ route('categories.update', $category->id) }}" method="post">
            @method('put')
            @csrf
            <input type="hidden" name="id" value="{{ $category->id }}">
            @include('categories.partials.item_form')
            <button class="btn btn-primary mt-3" type="submit">Update</button>
            <a  href="{{ route('categories.index') }}" class="btn btn-secondary mt-3">Cancel</a>
        </form>
    </div>
</div>
@endsection
