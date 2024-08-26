@extends('layout.main')

@section('content')
<div class="row w-100 m-0">
    <div class="content-wrapper full-page-wrapper d-flex align-items-center auth login-bg">
      <div class="card col-lg-4 mx-auto">
        <div class="card-body px-5 py-5">
          <h3 class="card-title text-left mb-3">{{ __('auth.login_title') }}</h3>
          <form action="{{ Route('authenticate') }}" method="post">
            @csrf
            @if(session()->has('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @elseif (session()->has('error'))
                <div class="alert alert-danger" role="alert">
                    {!! session('error') !!}
                </div>
            @endif
            <div class="form-group">
              <label>Email <span class="text-danger font-bold">*</span></label>
              <input type="text" name="email" class="form-control p_input @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
              @error('email')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>
            <div class="form-group">
              <label>Password <span class="text-danger font-bold">*</span></label>
              <input type="password" name="password" class="form-control p_input">
              @error('password')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>
            <div class="form-group d-flex align-items-center justify-content-between">
                <div class="form-check">
                  <label class="form-check-label" for="remember_me">
                    <input type="checkbox" id="remember_me" name="remember" class="form-check-input"> {{ __('auth.remember_title') }} </label>
                </div>
                <a href="{{ route('password.request') }}" class="forgot-pass">{{ __('auth.forgot_password_title') }}</a>
              </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary btn-block enter-btn">{{ __('auth.login_title') }}</button>
            </div>
            <p class="sign-up">{{ __('auth.login_to_register') }}<a href="{{ Route('register') }}"> Sign Up</a></p>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
