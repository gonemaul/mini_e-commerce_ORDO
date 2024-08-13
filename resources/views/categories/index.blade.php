
@extends('layout.main')

@section('content')
{{-- CDN --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/1b48e60650.js" crossorigin="anonymous"></script>

{{-- Local style --}}
<link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/modal.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/alert.css') }}">

<ul class="notifications"></ul>
<script src="{{ asset('assets/js/alert.js') }}"></script>

{{-- Alert --}}
@if(session()->has('success'))
    <input type="hidden" id="myElement" message="{{ session('success') }}">
    <script>
        var element = document.getElementById('myElement');
        var message = element.getAttribute('message');
        createToast('success', message);
    </script>
@elseif(session()->has('error'))
    <input type="hidden" id="myElement" message="{{ session('error') }}">
    <script>
        var element = document.getElementById('myElement');
        var message = element.getAttribute('message');
        createToast('error', message);
    </script>
@endif
@if(session()->has('failures'))
    @foreach (session()->get('failures') as $failure)
        @foreach($failure->errors() as $error)
        <input type="hidden" id="myElement" row="Baris {{ $error }} ">
            <script>
                var element = document.getElementById('myElement');
                var row = element.getAttribute('row');
                createToast('error', row);
            </script>
        @endforeach
    @endforeach
@endif
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Modal Import --}}
<input type="checkbox" id="toggle" checked>
<div class="wrapper">
    <label for="toggle">
    <i class="cancel-icon fas fa-times"></i>
    </label>
    {{-- <div class="icon"><i class="far fa-envelope"></i></div> --}}
    <div class="content">
      <header>Import Category</header>
      <p>Download Import Category Template if you don't have one</p>
    </div>
    <form action="{{ route('categories.import') }}" method="POST" id="modal" enctype="multipart/form-data">
        @csrf
        <input type="file" class="file_up" name="file_up" accept=".xls,.xlsx" required hidden>
        <div class="button">
            <a id="upload_file" class="btn btn-primary mb-2"><i class="fa-solid fa-cloud-arrow-up"></i>Upload File</a>
            <a id="templates" href="{{ route('categories.templates') }}" class="btn btn-success mb-2"><i class="fa-solid fa-cloud-arrow-down"></i>Download Template</a>
            {{-- <button type="submit" name="import" class="btn btn-primary">Submit</button> --}}
        </div>
    </form>
    <div class="text">We do not share your information.</div>
</div>


<div class="content-wrapper">
  <div class="p-4" style="background-color: #191c24;border-radius:0.5rem">
    <div class="d-flex justify-content-between mb-3">
      <h3 class="my-auto">Categories List</h3>
      <div class="d-block">
          <a class="btn btn-outline-primary" href="{{ route('categories.create') }}" style="font-size:1rem;font-weight:500;align-items:center;width:100%"><i class="fa-solid fa-plus"></i> Add Category</a>
          <div class="d-flex mt-2">
            <label for="toggle" class="btn btn-outline-success mb-0 mr-2" style="font-size:1rem;font-weight:500;align-items:center"><i class="fa-solid fa-cloud-arrow-down import"></i>Import</label>
            <a class="btn btn-outline-warning" href="{{ route('categories.export') }}" style="font-size:1rem;font-weight:500;align-items:center"><i class="fa-solid fa-cloud-arrow-up"></i>Export</a>
      </div>
  </div>
    </div>
    <div class="table-responsive">
        <table class="display hover row-border" id="tabel">
            <thead>
              <tr>
                <th class="text-center" style="font-weight:600;"> No </th>
                <th class="text-center" style="font-weight:600;"> Category Name </th>
                <th class="text-center" style="font-weight:600;"> Total Product </th>
                <th class="text-center" style="font-weight:600;"> Action </th>
              </tr>
            </thead>
              <tbody>
            </tbody>
        </table>
    </div>
  </div>
</div>
<script src="{{ asset('assets/vendors/DataTables/datatables.min.js') }}"></script>
<script>
    $(document).ready(function(){
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#upload_file').click(function(){
            $('.file_up').trigger('click');
        })
        $('.file_up').change(function(){
            $('#modal').submit();
            $('#templates').disable();
            $('#upload_file').disable();
        });
        let table = new DataTable('#tabel', {
                prosessing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: '{{ route('categories.load_data') }}',
                    type: 'POST',
                    headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                },
                paging: true,
                columns: [
                        {
                            name: 'no',
                            data: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        },
                        {
                            name: 'name',
                            data: 'name',
                            orderable: true
                        },
                        {
                            name: 'count_product',
                            data: 'count_product',
                            className: 'text-center'
                        },
                        {
                            name: 'Action',
                            data: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'justify-content-center d-flex'
                        }
                ]
        });
    })
</script>
@endsection
