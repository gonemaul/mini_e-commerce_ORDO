@extends('layout.main')

@section('content')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/1b48e60650.js" crossorigin="anonymous"></script>
<script src="{{ asset('assets/js/file-upload.js') }}"></script>

<ul class="notifications mt-3"></ul>
<link rel="stylesheet" href="{{ asset('assets/css/modal.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/alert.css') }}">
<script src="{{ asset('assets/js/alert.js') }}"></script>
<style>
    .invalid-feedback{
        display: block
    }
    .detail-field{
        display: block;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 85%;
        color: #c1c1c1;
    }
    .box-item {
        background-color: #191c24;
        border-radius:0.5rem
    }
    #profile_image{
        margin-left: 1rem;
    }
    @media (max-width:767px){
        #profile_image{
            margin-left:0;
            text-align: center;
        }
        .hapus_akun{
            text-align: center;
            margin-top: 10px;
        }
    }
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- Modal --}}
<input type="checkbox" id="toggle" checked>
{{-- <div class="modal_luar"> --}}
    <div class="wrapper text-left">
        <label for="toggle">
        <i class="cancel-icon fas fa-times"></i>
        </label>
        <div class="top">
            <p>{{ __('general.password_field') }}</p>
        </div>
        <div id="modal">
            @csrf
            <div class="form-group">
                <label>Password <span class="text-danger font-bold">*</span></label>
                <input type="password" id="password_confirm" name="password" class="form-control" style="background-color: #474c5a;color:#ffff" placeholder="Your Password" required autofocus>
                @error('password')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
            </div>
            <div class="tombol">
            </div>
        </div>
    </div>
{{-- </div> --}}

@if(session()->has('error'))
    <script>
        var message = "{{ session()->get('error') }}";
        createToast('error', message);
    </script>
@elseif (session()->has('success'))
    <script>
        var message = "{{ session()->get('success') }}";
        createToast('success', message);
    </script>
@endif
<div class="content-wrapper">

    {{-- Detail Akun --}}
    <div class="row p-4 mb-3 box-item">
        <div class="col-md-6 ps-3">
            <h4 class="align-center text-center">{{ __('user.title_detail') }}</h4>
        </div>
        <div class="col md-6 ps-3 ">
            <form action="{{ Route('update-profile') }}" method="post"  enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $user->id }}">
                <div class="form-group">
                    <div class="profileImg mt-3" id="profile_image">
                        @if ($user->profile_image)
                            <img class="img-preview rounded-circle" style="width: 100px;height:100px" src="{{ asset('storage/' . $user->profile_image) }}">
                        @else
                            <img class="img-preview rounded-circle" style="width: 100px;height:100px" src="https://ui-avatars.com/api/?name={{ $user->name }}&color=7F9CF5&background=EBF4FF">
                        @endif
                    </div>
                    <div class="form-group mt-4">
                        <input type="file" id="profile" name="profile_image" class="file-upload-default"  onchange="imgPreview()">
                        <div class="input-group col-xs-12">
                        <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                        <span class="input-group-append">
                            <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                        </span>
                        </div>
                    </div>
                    @error('profile_image')
                        <div class="invalid-feedback">
                        {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="name">{{ __('general.name') }}</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Name" value="{{ $user->name }}">
                    @error('name')
                        <div class="invalid-feedback">
                        {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Email" value="{{ $user->email }}">
                    <div class="detail-field">
                        {{ __('user.info_profile') }}
                    </div>
                    @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                        </div>
                    @enderror
                </div>
                <button class="btn btn-primary" onclick="return confirm('What are you sure? ..')" type="submit">{{ __('general.update') }}</button>
            </form>
        </div>
    </div>

    {{-- Change Password --}}
    <div class="row mb-3 p-4 box-item" id="changePassword">
        <div class="col-md-6 ps-3 text-center">
            <h4 class="align-center">{{ __('passwords.change') }}</h4>
        </div>
        <div class="col-md-6">
            <form action="{{ Route('update-password') }}" method="post">
                @csrf
                <input type="hidden" name="id" value="{{ $user->id }}">
                <div class="form-group">
                    <label for="current_password">{{ __('passwords.current') }}</label>
                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" id="current_password" placeholder="{{ __('passwords.current') }}">
                    @error('current_password')
                        <div class="invalid-feedback">
                        {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password">{{ __('passwords.new') }}</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="{{ __('passwords.new') }}" >
                    @error('password')
                        <div class="invalid-feedback">
                        {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password_confirmation">{{ __('passwords.confirm') }}</label>
                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" id="password_confirmation" placeholder="{{ __('passwords.confirm') }}" >
                    @error('password_confirmation')
                        <div class="invalid-feedback">
                        {{ $message }}
                        </div>
                    @enderror
                </div>
                <button class="btn btn-primary" type="submit">{{ __('general.update') }}</button>
            </form>
        </div>
    </div>

    {{-- Browser Session --}}
    <div class="row p-4 mb-3 box-item">
        <div class="col-md-6 ps-3 text-center">
            <h4 class="align-center">{{ __('auth.sesi_title') }}</h4>
        </div>
        <div class="col md-6 ps-3 ">
            @foreach ($sessions as $session)
            <div class="item d-flex py-3">
                <div class="icon mr-3" style="font-size: 1.5rem;">
                    <i class="fa-solid fa-desktop"></i>
                </div>
                <div class="text">
                    <p class="m-0">{{ $session->platform }} - {{ $session->browser }}</p>
                    <p class="m-0">{{ $session->ip_address }}
                        @if($session->id == session()->getId())
                        <span class="text-success ml-2">{{ __('auth.this_sesi') }}</span>
                        @endif
                    </p>
                </div>
            </div>
            @endforeach
        <button class="btn btn-primary" id="btn-lod" type="submit">{{ __('auth.btn_lod') }}</button>
        </div>
    </div>

    {{-- Delete Akun --}}
    <div class="row p-4 mb-3 box-item">
        <div class="col-md-6 ps-3 text-center">
            <h4 class="align-center">{{ __('auth.delete_account.title') }}</h4>
        </div>
        <div class="col md-6 ps-3 hapus_akun">
            <p>{{ __('auth.delete_account.info')}}</p>
            <button class="btn btn-danger mt-2" id="btn-delete">{{ __('auth.delete_account.title') }}</button>
        </div>
    </div>
</div>

<script>
    $('#delete-account').click(function() {
        $('#form_delete').removeClass('d-none')
        $('#btn_delete').addClass('d-none')
    })
    $('#btn-delete').click(function(){
        $('#toggle').prop('checked', false);
        $('.tombol').html('<button class="btn btn-danger mt-3" id="submit_delete" name="submit_delete" onclick="submit_delete()">{{ __('general.delete') }}</button>')
    })
    $('#btn-lod').click(function(){
        $('#toggle').prop('checked', false);
        $('.tombol').html('<button class="btn btn-primary mt-3" id="submit_lod" onclick="submit_lod()">Logout</button>')
    })
    function submit_delete(){
        var password = $('#password_confirm').val();
        if(password != ''){
            const confirm_delete = confirm("{{__('auth.delete_account.confirm')}}");
            if(confirm_delete){
                $.ajax({
                        type: 'POST',
                            url: '/delete-account',
                            headers:{
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                        data:{
                            password: password
                        },
                        success: function(response){
                            $('#toggle').prop('checked', true);
                            if(response.status == 'success'){
                                createToast('success', response.pesan);
                                setTimeout(function() {
                                    window.location.href = '/login';
                                }, 5000);
                            } else{
                                createToast('error', response.pesan);
                                $('#password_confirm').val('')
                            }
                        },
                        error: function (error){
                            console.log(error)
                            createToast('error', error);
                        }
                })
            } else{
                $('#toggle').prop('checked', true);
                $('#password_confirm').val('')
            }
        } else {
            createToast('error', 'Password is required');
        }
    }
    function submit_lod(){
        var password = $('#password_confirm').val();
        if(password != ''){
            $.ajax({
                    type: 'POST',
                        url: '/logout-other',
                        headers:{
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    data:{
                        password: password
                    },
                    success: function(response){
                        $('#toggle').prop('checked', true);
                        if(response.status == 'success'){
                            createToast('success', response.pesan);
                            setTimeout(function() {
                                location.reload();
                            }, 5000);
                        } else{
                            createToast('error', response.pesan);
                            $('#password_confirm').val('')
                        }
                    },
                    error: function (error){
                        console.log(error)
                        createToast('error', error);
                    }
            })
        } else{
            createToast('error', 'Password is required');
        }
    }
    function imgPreview() {
        const image = document.querySelector("#profile");
        const imgPreview = document.querySelector(".img-preview");

        // imgPreview.style.display = 'block';

        const oFReader = new FileReader();
        oFReader.readAsDataURL(image.files[0]);

        oFReader.onload = function(oFREvent) {
            imgPreview.src = oFREvent.target.result;
        }
    }
</script>
@endsection
