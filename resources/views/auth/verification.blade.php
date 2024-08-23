<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
<style>
    .content-wrapper    {
        height: 100vh;
        width: 100%;
    }
</style>

    <div class="content-wrapper full-page-wrapper d-flex align-items-center">
      <div class="card col-lg-4 mx-auto">
        <div class="card-body px-5 py-5">
          <h6>Please check your inbox for a verification account.</h6>
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
            <div class="text-center">
              <button type="submit" class="btn btn-primary btn-block enter-btn">Resend Link</button>
            </div>
          </form>
        </div>
      </div>
    </div>
