   <!-- plugins:css -->
   <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
   <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">

   <!-- Layout styles -->
   <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
   <!-- End layout styles -->
   <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
<style>
    .banner {
        width: 100%;
        height: 30px;
        background-color: #e43131;
        color: #ffff;
        text-align: center;
        align-content: center;
        font-size: 1rem;
        font-weight: 500;
        padding: 0 1.5rem;
    }
    .content{
        height: 100vh;
    }
    .content-wrapper {
        height: calc(100vh - 30px);
    }
</style>
<div class="content">
    <div class="banner">
        <marquee behavior="" direction="">Mohon maaf website sedang dalam perbaikan !!!...   <span class="ml-3">contact admin : admin@gonemaul.my.id</span></marquee>

    </div>
    <div class="content-wrapper full-page-wrapper d-flex align-items-center auth login-bg">
        <div class="card col-lg-4 mx-auto">
          <div class="card-body px-5 py-5">
            <h3 class="card-title text-left mb-3">Login</h3>
              <div class="form-group">
                <label>Email <span class="text-danger font-bold">*</span></label>
                <input type="text" class="form-control p_input @error('email') is-invalid @enderror" value="{{ old('email') }}">
              </div>
              <div class="form-group">
                <label>Password <span class="text-danger font-bold">*</span></label>
                <input type="password" class="form-control p_input">
              </div>
              <div class="text-center">
                <button class="btn btn-primary btn-block enter-btn">Login</button>
              </div>
              <p class="sign-up">Don't have an Account?<a href="#"> Sign Up</a></p>
          </div>
        </div>
    </div>
</div>
