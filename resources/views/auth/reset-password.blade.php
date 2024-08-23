@extends('layout.main')

@section('content')
    <div class="content-wrapper full-page-wrapper d-flex align-items-center auth login-half-bg">
        <div class="card col-lg-4 mx-auto p-5">
            <form action="{{ route('password.update') }}" method="post">
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
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ request()->email }}">
                <label>Please enter new password</label>
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" class="form-control" id="password" name="password" required autofocus>
                    @error('password')
                        <div class="invalid-feedback d-block">
                        {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required autofocus>
                </div>
                <button type="submit" class="btn btn-primary">Update Password</button>
            </form>
        </div>
    </div>
@endsection
