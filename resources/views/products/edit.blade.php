
@extends('layout.main')

@section('content')
    <div class="content-wrapper">
        <div class="p-4" style="background-color: #191c24;border-radius:0.5rem">
            <div class="title mb-5">
                <h4>Edit Category</h4>
            </div>
            <form action="{{ route('products.update', $product->id) }}" method="post" enctype="multipart/form-data">
                @method('put')
                @csrf
                @include('products.partials.item_form')
                <button type="submit" class="btn btn-primary">Update Product</button>
                <a  href="{{ Route('products.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
