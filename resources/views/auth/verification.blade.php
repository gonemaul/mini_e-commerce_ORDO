<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
<style>
    .content-wrapper    {
        height: 100vh;
        width: 100%;
    }
    .invalid-feedback{
        display: block;
    }
    .info{
        color: #6c7293;
        margin-bottom: 10px;
    }
</style>

    <div class="content-wrapper full-page-wrapper d-flex align-items-center auth login-half-bg">
      <div class="card col-lg-4 mx-auto">
        <div class="card-body px-5 py-5">
          <form action="{{ route('verification.send') }}" method="post">
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
            <form action="">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" name="email" class="form-control p_input @error('email') is-invalid @enderror" value="{{ session('email') ?? '' }}" required autofocus>
                    @error('email')
                        <div class="invalid-feedback">
                        {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="invalid-feedback info">
                    {{ __('auth.info_verify') }}
                </div>
                <div class="text-center">
                  <button type="submit" class="btn btn-primary btn-block enter-btn">{{ __('auth.btn_resend') }}</button>
                </div>
            </form>
          </form>
        </div>
      </div>
    </div>
<script>

</script>
