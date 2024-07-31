<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/1b48e60650.js" crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
<script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>


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
    .imageContainer .marked-for-removal{
        opacity: 0.2; /* Mengurangi opasitas gambar */
        border: 2px solid rgb(0, 0, 0); /* Menambahkan garis tepi merah */
        background-color: rgba(0, 0, 0, 0.508);
    }
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

{{-- Name Produk --}}
    <div class="form-group">
        <label for="name">Product Name</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" autofocus value="{{ old('name', $product->name ?? '') }}">
    </div>
    @error('name')
    <div class="invalid-feedback">
      {{ $message }}
    </div>
    @enderror

{{-- Category --}}
    <div class="form-group">
        <label for="category">Category</label>
        <select class="form-control" id="category" name="category" value="{{ old('category', $product->category ?? '') }}">
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" {{ (old('category_id', $product->category_id ?? '') == $category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
        @endforeach
        </select>
    </div>

{{-- Price --}}
    <div class="form-group">
        <label for="price">Price</label>
        <input type="text" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price ?? '') }}">
    </div>
    @error('price')
    <div class="invalid-feedback">
      {{ $message }}
    </div>
    @enderror

{{-- Stock --}}
    <div class="form-group">
        <label for="stock">Stock</label>
        <input type="text" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $product->stock ?? '') }}">
    </div>
    @error('stock')
    <div class="invalid-feedback">
      {{ $message }}
    </div>
    @enderror

{{-- Images --}}
    <div class="form-group mt-4">
        <label for="images">Product Image</label>
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
    <div class="mb-3 imageContainer mt-3" id="imageContainer">
        @if(isset($product) && $product->productImage->isNotEmpty())
            @foreach ($product->productImage as $image)
                <div class="image-box">
                    <img src="{{ asset('storage/' . $image->image) }}" class="rounded">
                    <input class="d-none" type="checkbox" id="remove_image_{{ $loop->index }}" name="removed[]" value="{{ $image->id }}">
                    <label class="remove-image" for="remove_image_{{ $loop->index }}"><i class="fa fa-trash"></i></label>
                </div>
            @endforeach
        @endif
    </div>

{{-- Description --}}
    <div class="form-group mb-4">
        <label for="description">Description</label>
        <input id="description" type="hidden" name="description" value="{{ old('description', $product->description ?? '') }}" required>
        <trix-editor input="description"></trix-editor>
    </div>
    @error('description')
    <div class="invalid-feedback">
      {{ $message }}
    </div>
    @enderror
<script>
        const price =document.getElementById('price')

        price.addEventListener('input', function (e) {
            let value = e.target.value;
            value = value.replace(/[^0-9]/g, ''); // Hanya mengizinkan angka
            if (value) {
                value = parseInt(value).toLocaleString('id-ID'); // Format angka dengan pemisah ribuan
            }
            e.target.value = value;
        });

        var imagesToRemove = [];

        $('.remove-image').on('click', function() {
            var imageName = $(this).data('image');
            var parentDiv = $(this).parent();

            if (parentDiv.hasClass('marked-for-removal')) {
                parentDiv.removeClass('marked-for-removal');
                imagesToRemove = imagesToRemove.filter(function(img) { return img !== imageName; });
            } else {
                parentDiv.addClass('marked-for-removal');
                imagesToRemove.push(imageName);
            }
            console.log(imagesToRemove);
        });

        $('#images').on('change', function() {
            var files = this.files;
            var preview = $('#imageContainer');
            // preview.empty();
            var fileArray = Array.from(files);
            if(files) {
                $.each(fileArray, function(i, file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var div = $('<div>').addClass('image-box');
                        var img = $('<img>').addClass('rounded').attr('src', e.target.result);
                        var button = $('<button>').addClass('remove-image').attr('data-index', i).html('<i class="fa fa-trash"></i>');
                        button.on('click', function() {
                            var index = $(this).data('index');
                            fileArray.splice(index, 1);
                            updateFileInput(fileArray);
                            $(this).parent().remove();
                        });
                        div.append(img).append(button);
                        preview.append(div);
                    };
                    reader.readAsDataURL(file);
                });
            }
        });

        function updateFileInput(fileArray) {
            var dataTransfer = new DataTransfer();
            fileArray.forEach(file => dataTransfer.items.add(file));
            $('#images')[0].files = dataTransfer.files;
        }
</script>
<script src="{{ asset('assets/js/file-upload.js') }}"></script>
