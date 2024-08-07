@extends('layout.main')

@section('content')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
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

<meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="content-wrapper">
        <div class="p-4" style="background-color: #191c24;border-radius:0.5rem">
            <div class="d-flex justify-content-between mb-3">
                <h3 class="my-auto">Products List</h3>
                <a class="btn btn-outline-primary btn-icon-text py-auto text-center" href="{{ Route('products.create') }}" style="font-size:1rem;font-weight:500"><i class="fa-solid fa-plus"></i> Add Product</a>
            </div>
            <div class="table-responsive">
                <div class="text-center" id="loading" style="width:100%;">
                    <img src="{{ asset('loading.gif') }}" style="height: 5rem">
                    <p class="mb-0" style="font-weight: 600">Loading</p>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post("{{ url('products/load') }}",{}, function(data,status){
                    $('.table-responsive').html(data);
            })
        })
    </script>
@endsection
