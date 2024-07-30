
@extends('layout.main')

@section('content')
<link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
<script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
<style>
    trix-toolbar [data-trix-button-group="file-tools"]{
        display: none
    }
    trix-toolbar .trix-button{
        background-color: rgba(167, 167, 167, 0.695);
    }
</style>

    <div class="content-wrapper">
        <div class="p-4" style="background-color: #191c24;border-radius:0.5rem">
            <div class="title mb-5">
                <h4>Edit Category</h4>
            </div>
            <form action="{{ route('products.update', $product->id) }}" method="post">
                @method('put')
                @csrf
                <div class="mb-3 imageContainer mt-3" id="imageContainer ">
                    @foreach ($product->productImage as $img)
                        <div class="image d-inline text-center">
                            <img class="rounded mr-2" style="width: 80px;height:80px;border: 1px solid #9e9e9e;" src="{{ asset('storage/' . $img->image) }}">
                            <i class="mdi mdi-close-circle-outline"></i>
                        </div>
                    @endforeach
                </div>
                <div class="form-group">
                    <label for="name">Product Name</label>
                    <input type="text" class="form-control" id="name" name="name" required value="{{ $product->name }}">
                </div>
                @error('name')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror

                <div class="form-group">
                    <label for="category">Category</label>
                    <select class="form-control" id="category" name="category">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $category->id == $product->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="text" class="form-control" id="price" name="price" required value="{{ number_format($product->price, 0, ',', '.') }}">
                </div>
                @error('price')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror

                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="text" class="form-control" id="stock" name="stock" required value="{{ $product->stock }}">
                </div>
                @error('stock')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror

                <div class="form-group mb-4">
                    <label for="description">Description</label>
                    <input id="description" type="hidden" name="description" value="{{ $product->description }}" required>
                    <trix-editor input="description"></trix-editor>
                </div>
                @error('description')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
                <button type="submit" class="btn btn-primary">Update Product</button>
                <a  href="{{ Route('products.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
