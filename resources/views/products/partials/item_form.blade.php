<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/1b48e60650.js" crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
<script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
<link rel="stylesheet" href="{{ asset('assets/css/uploader.css') }}">
<link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
<style>
    trix-toolbar [data-trix-button-group="file-tools"]{
        display: none
    }
    trix-toolbar .trix-button{
        background-color: rgba(167, 167, 167, 0.695);
    }
    .form-control:focus{
        color: #dddddd;
    }
    select.form-control{
        color: #dddddd;
    }
    .form-group {
        margin-top: 1rem;
        margin-bottom: 0;
    }
    .invalid-feedback {
        display: block !important;
    }
    .imageContainer {
        display: flex;
        flex-wrap: wrap;
    }
    /* .marked-for-removal .file-details{
        opacity: 0.2;
        border: 2px solid rgb(0, 0, 0);
        background-color: rgba(0, 0, 0, 0.508);
    } */
    .marked-for-removal .remove-image{
        opacity: 0;
    }
    .imageContainer .image-box{
        position: relative;
        margin: 10px;
        width: 80px;
        height: 80px;
    }
    .image-box img{
        width: 100%;
        height: 100%;
        object-fit: cover;
        border:1px solid #9e9e9e;
    }
    .image-box .remove-image{
        position: absolute;
        top: 2px;
        right: 3px;
        background-color: transparent;
        border-radius: 3px;
        border: none;
        cursor: pointer;
    }

    .image-box .remove-image i{
        color: #fff;
        transition: .3s ease;
    }

    .image-box:hover .remove-image i{
        color: #cb0909;
        padding: 20px 18px;
        font-size: 30px
    }
</style>
<input type="hidden" name="path_image" id="path_image" value="{{ old('path_image') }}">
@if ($errors->any())
    <script>
        error()
    </script>
@enderror
{{-- Name Produk --}}
    <div class="form-group">
        <label for="name">{{ __('product.name') }} <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" autofocus value="{{ old('name', $product->name ?? '') }}">
    </div>
    @error('name')
    <div class="invalid-feedback">
      {{ $message }}
    </div>
    @enderror

{{-- Category --}}
    <div class="form-group">
        <label for="category">{{ __('general.category') }} <span class="text-danger">*</span></label>
        <select class="form-control" id="category" name="category" value="{{ old('category', $product->category ?? '') }}">
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" {{ (old('category_id', $product->category_id ?? '') == $category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
        @endforeach
        </select>
    </div>

{{-- Price --}}
    <div class="form-group">
        <label for="price">{{ __('general.price') }} <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price ?? '') }}">
    </div>
    @error('price')
    <div class="invalid-feedback">
      {{ $message }}
    </div>
    @enderror

{{-- Stock --}}
    <div class="form-group">
        <label for="stock">{{ __('general.stock') }} <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $product->stock ?? '') }}">
    </div>
    @error('stock')
    <div class="invalid-feedback">
      {{ $message }}
    </div>
    @enderror

{{-- Description --}}
    <div class="form-group">
        <label for="description">{{ __('general.descriptions') }} <span class="text-danger">*</span></label>
        <input id="description" type="hidden" name="description" value="{{ old('description', $product->description ?? '') }}" required>
        <trix-editor input="description"></trix-editor>
    </div>
    @error('description')
    <div class="invalid-feedback">
    {{ $message }}
    </div>
    @enderror

{{-- Images --}}
@error('images')
<div class="invalid-feedback">
  {{ $message }}
</div>
@enderror

{{-- <div class="mb-3 imageContainer mt-3" id="imageContainer">
    @if(isset($product) && $product->productImage->isNotEmpty())
        @foreach ($product->productImage as $image)
            <div class="image-box">
                <img src="{{ asset('storage/' . $image->image) }}" class="rounded">
                <input class="d-none" type="checkbox" id="remove_image_{{ $loop->index }}" name="removed[]" value="{{ $image->id }}">
                <label class="remove-image" for="remove_image_{{ $loop->index }}"><i class="fa fa-trash"></i></label>
            </div>
        @endforeach
    @endif
</div> --}}

<script>
//         var imagesToRemove = [];

//         $('.remove-image').on('click', function() {
//             var imageName = $(this).data('image');
//             var parentDiv = $(this).parent();

//             if (parentDiv.hasClass('marked-for-removal')) {
//                 parentDiv.removeClass('marked-for-removal');
//                 imagesToRemove = imagesToRemove.filter(function(img) { return img !== imageName; });
//             } else {
//                 parentDiv.addClass('marked-for-removal');
//                 imagesToRemove.push(imageName);
//             }
//             console.log(imagesToRemove);
//         });
// </script>

