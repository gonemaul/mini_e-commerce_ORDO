@extends('layout.main')

@section('content')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="{{ asset('assets/js/file-upload.js') }}"></script>
<link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
<script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    <style>
        trix-toolbar [data-trix-button-group="file-tools"]{
            display: none
        }
        trix-toolbar .trix-button{
            background-color: rgba(167, 167, 167, 0.695);
        }
        .form-group {
            margin-top: 1rem;
            margin-bottom: 0;
        }
        .invalid-feedback {
            display: block !important;
        }
    </style>

    <div class="content-wrapper">
        <div class="p-4" style="background-color: #191c24;border-radius:0.5rem">
            <div class="title mb-5">
                <h4>Add Product</h4>
            </div>
            <form action="{{ Route('products.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name">Product Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" autofocus value="{{ old('name') }}">
                </div>
                @error('name')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror

                <div class="form-group">
                    <label for="category">Category</label>
                    <select class="form-control" id="category" name="category" value="{{ old('category') }}">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="proce">Price</label>
                    <input type="text" class="form-control @error('price') is-invalid @enderror" id="proce" name="price" value="{{ old('price') }}">
                </div>
                @error('price')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror

                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="text" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock') }}">
                </div>
                @error('stock')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror

                <div class="form-group mt-4">
                    <label for="images">Image</label>
                    <input type="file" id="images" name="images[]" multiple class="file-upload-default @error('images') is-invalid @enderror">
                    <div class="input-group col-xs-12">
                        <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                        <span class="input-group-append">
                            <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                        </span>
                    </div>
                </div>
                @error('image')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
                <div class="mb-3 imageContainer mt-3" id="imageContainer "></div>

                <div class="form-group mb-4">
                    <label for="description">Description</label>
                    <input id="description" type="hidden" name="description" value="{{old('description')}}" required>
                    <trix-editor input="description"></trix-editor>
                </div>
                @error('description')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror

                <button type="submit" class=" btn btn-primary">Add Product</button>
                <a  href="{{ Route('products.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>

    <script>
        $('#images').change(event => {
        if(event.target.files){
            let filesAmount = event.target.files.length;
            $('.imageContainer').html("");

            for(let i = 0; i < filesAmount; i++){
                let reader = new FileReader();
                reader.onload = function(event){
                    let img = `<img class="rounded mr-2" style="width: 80px;height:80px; border:1px solid #9e9e9e" src="${event.target.result}">`;
                    $(".imageContainer").append(img);
                }
                reader.readAsDataURL(event.target.files[i]);
            }}});
    </script>
@endsection
