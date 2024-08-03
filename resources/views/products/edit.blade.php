
@extends('layout.main')

@section('content')
<style>
    form .file-extension .image_removed{
        opacity: 0.2; /* Mengurangi opasitas gambar */
        border: 2px solid rgb(0, 0, 0); /* Menambahkan garis tepi merah */
        background-color: rgba(0, 0, 0, 0.508);
    }
</style>
    <div class="content-wrapper">
        <div class="p-4" style="background-color: #191c24;border-radius:0.5rem">
            <div class="title mb-5">
                <h4>Edit Category</h4>
            </div>
            <form action="{{ route('products.update', $product->id) }}" method="post" enctype="multipart/form-data">
                @method('put')
                @csrf
                @include('products.partials.item_form')
                <button type="submit" class="btn btn-primary mt-3 mr-3" id="submit">Update Product</button>
                <a  href="{{ Route('products.index') }}" class="btn btn-secondary mt-3">Cancel</a>
            </form>
            <form action="" enctype="multipart/form-data" id="form_image">
                <div class="file-uploader">
                    <div class="uploader-header">
                      <h2 class="uploader-title">File Uploader</h2>
                      <h4 class="file-completed-status"></h4>
                    </div>
                    <ul class="file-list">
                        @if(isset($product) && $product->productImage->isNotEmpty())
                            @foreach ($product->productImage as $image)
                                <li class="file-item">
                                    <div class="file-extension">
                                        <img src="{{ asset('storage/' . $image->image) }}" class="file-preview" id="image_{{ $loop->index }}">
                                    </div>
                                    <div class="file-content-wrapper">
                                    <div class="file-content">
                                        <div class="file-details">
                                            <div class="file-info">
                                                <small class="file-size" style="color:#ffff " id="text_{{ $loop->index }}">Current Image</small>
                                            </div>
                                        </div>
                                        <label class="remove-image" image_path='{{ $image->image }}' id="{{ $loop->index }}">
                                            <i class='bx bxs-trash'></i>
                                        </label>
                                    </div>
                                    <div class="file-progress-bar">
                                        <div class="file-progress"></div>
                                    </div>
                                    </div>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                    <div class="file-upload-box">
                      <h2 class="box-title">
                        <span class="file-instruction">Drag files here or</span>
                        <span class="file-browse-button">browse</span>
                      </h2>
                      <input class="file-browse-input" name="files[]" type="file" multiple hidden>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('assets/js/uploader.js') }}"></script>
    <script>
        var imagesToRemove = [];

        $('.remove-image').on('click', function() {
            const path = $(this).attr('image_path');
            const status = document.querySelector('#text_' +  $(this).attr('id'))
            const image = document.querySelector('#image_' + $(this).attr('id'))
            var parentDiv = $(this).parent();
            const remove = handleDeleteFiles(path);

            remove.addEventListener('readystatechange', () => {
                if(remove.readyState === XMLHttpRequest.DONE && remove.status === 200) {
                    var response = JSON.parse(remove.responseText);
                    status.innerText = response.status;
                    status.style.color = response.color;
                    image.classList.add('image_removed')
                    parentDiv.addClass('marked-for-removal');
                }
            })
        });
    </script>
@endsection
