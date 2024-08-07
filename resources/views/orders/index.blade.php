@extends('layout.main')

@section('content')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/1b48e60650.js" crossorigin="anonymous"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="content-wrapper">
    <div class="p-4" style="background-color: #191c24;border-radius:0.5rem">
        <div class="d-flex justify-content-between mb-3">
            <h3 class="my-auto">Orders List</h3>
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
        $.post("{{ url('orders/load') }}",{}, function(data,status){
                $('.table-responsive').html(data);
        })
    })
</script>
@endsection
