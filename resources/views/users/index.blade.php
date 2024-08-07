@extends('layout.main')

@section('content')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/1b48e60650.js" crossorigin="anonymous"></script>

<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="content-wrapper">
  <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <div class="text-center" id="loading" style="width:100%;">
                <img src="{{ asset('loading.gif') }}" style="height: 5rem">
                <p class="mb-0" style="font-weight: 600">Loading</p>
            </div>
          </div>
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
        $.post("{{ url('users/load') }}",{}, function(data,status){
                $('.table-responsive').html(data);
        })
    })
</script>
@endsection
