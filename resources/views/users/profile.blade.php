@extends('layout.main')

@section('content')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/1b48e60650.js" crossorigin="anonymous"></script>
<script src="{{ asset('assets/js/file-upload.js') }}"></script>
<ul class="notifications"></ul>
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
</style>
<div class="content-wrapper">
    @if (url()->current() == Route('profile'))
        @if(session()->has('error'))
        <input type="hidden" id="myElement" message="{{ session('error') }}">
            <script>
                var element = document.getElementById('myElement');
                var message = element.getAttribute('message');
                createToast('error', message);
            </script>
        @endif
        <div class="row px-4 py-4 mb-3" style="background-color: #191c24;border-radius:0.5rem">
            <div class="col-md-6 ps-3 text-center">
                <h4 class="align-center">{{ __('user.title_detail') }}</h4>
            </div>
            <div class="col md-6 ps-3 ">
                <form action="{{ Route('update-profile') }}" method="post"  enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $user->id }}">
                    <div class="form-group">
                        <div class="profileImg mt-3" style="margin-left: 1rem">
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

        <div class="row px-4 py-4 mb-3" style="background-color: #191c24;border-radius:0.5rem">
            <div class="col-md-12 ps-3 text-center" style="border-bottom: 3px solid #343434;">
                <h3 class="text-danger">{{ __('user.zone') }}</h3>
            </div>
            <div class="col-md-12 text-center" id="btn_delete">
                <button class="btn btn-outline-danger text-center mt-3 fs-3" id="delete-account">{{ __('user.delete_account') }}</button>
            </div>
            <div class="col md-12 ps-3 mt-3 d-none" id="form_delete">
                <form action="{{ Route('delete-account') }}" method="post"  enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="password">{{ __('general.password_field') }}</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Password">
                        @error('password')
                            <div class="invalid-feedback">
                            {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <button class="btn btn-outline-danger" onclick="return confirm('What are you sure? ..')" type="submit">{{ __('general.delete') }}</button>
                </form>
            </div>
        </div>
        <script>
            $('#delete-account').click(function() {
                $('#form_delete').removeClass('d-none')
                $('#btn_delete').addClass('d-none')
            })
        </script>
    @else
        @if(session()->has('error'))
        <input type="hidden" id="myElement" message="{{ session('error') }}">
            <script>
                var element = document.getElementById('myElement');
                var message = element.getAttribute('message');
                createToast('error', message);
            </script>
        @elseif (session()->has('success'))
            <input type="hidden" id="myElement" message="{{ session('success') }}">
            <script>
                var element = document.getElementById('myElement');
                var message = element.getAttribute('message');
                createToast('success', message);
            </script>
        @endif
        <div class="row mt-3 px-4 py-4" id="changePassword" style="background-color: #191c24;border-radius:0.5rem">
            <div class="col-md-6 ps-3 text-center">
                <h4 class="align-center">{{ __('passwords.change') }}</h4>
            </div>
            <div class="col-md-6">
                <form action="{{ Route('update-password') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $user->id }}">
                    <div class="form-group">
                        <label for="current_password">{{ __('passwords.current') }}</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" id="current_password" placeholder="Current Password">
                        @error('current_password')
                            <div class="invalid-feedback">
                            {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">{{ __('passwords.new') }}</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="New Password" >
                        @error('password')
                            <div class="invalid-feedback">
                            {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">{{ __('passwords.confirm') }}</label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" >
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
    @endif
</div>
<script>
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
