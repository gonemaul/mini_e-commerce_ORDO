@extends('layout.main')

@section('content')
    <div class="content-wrapper full-page-wrapper d-flex align-items-center auth login-half-bg">
        <div class="card col-lg-4 mx-auto p-5">
            <form action="{{ route('password.email') }}" method="post">
                @csrf
                @if(session()->has('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @elseif (session()->has('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                <label>{{ __('auth.info_text') }}</label>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" id="email" name="email" required autofocus>
                    @error('email')
                        <div class="invalid-feedback d-block">
                        {{ $message }}
                        </div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">{{ __('auth.btn_request') }}</button>
                <a href="{{ route('login') }}" class="btn btn-secondary">{{ __('general.cancel') }}</a>
            </form>
        </div>
    </div>
@endsection
