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
                @include('products.partials.item_form')
                <button type="submit" class=" btn btn-primary mt-3" id="submit">Add Product</button>
                <a class="btn btn-secondary mt-3" onclick="cekImage()" id="cancel-submit">Cancel</a>
            </form>
            <form action="" enctype="multipart/form-data" id="form_image">
                <div class="file-uploader">
                    <div class="uploader-header">
                      <h2 class="uploader-title">File Uploader</h2>
                      <h4 class="file-completed-status"></h4>
                    </div>
                    <ul class="file-list"></ul>
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
@endsection
