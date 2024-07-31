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
                <button type="submit" class=" btn btn-primary">Add Product</button>
                <a  href="{{ Route('products.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
