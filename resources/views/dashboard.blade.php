@extends('layout.main')

@section('content')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

@if(session()->has('success'))
    <script>
      swal("Good job!", {{ session('success') }}, "success");
    </script>
@endif

<div class="content-wrapper">
    <div class="row">
      <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="row mb-2">
              <div class="col-3">
                <div class="icon icon-box-warning">
                  <span class="mdi mdi-account-multiple icon-item"></span>
                </div>
              </div>
                <div class="d-flex align-items-center align-self-start my-auto col-9">
                  <h3 class="mb-0" style="font-size: 1.6rem;font-weight:500">89</h3>
                </div>
            </div>
            <h6 class="text-muted font-weight-normal">Total Users</h6>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="row mb-2">
              <div class="col-3">
                <div class="icon icon-box-danger">
                  <span class="mdi mdi-apps icon-item"></span>
                </div>
              </div>
                <div class="d-flex align-items-center align-self-start col-9 my-auto">
                  <h3 class="mb-0" style="font-size: 1.6rem;font-weight:500">76</h3>
                </div>
            </div>
            <h6 class="text-muted font-weight-normal">Total Category</h6>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="row mb-2">
              <div class="col-3">
                <div class="icon icon-box-primary">
                  <span class="mdi mdi-package-variant-closed icon-item"></span>
                </div>
              </div>
                <div class="d-flex align-items-center align-self-start col-9 my-auto">
                  <h3 class="mb-0" style="font-size: 1.6rem;font-weight:500">786</h3>
                </div>
            </div>
            <h6 class="text-muted font-weight-normal">Total Product</h6>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="row mb-2">
              <div class="col-3">
                <div class="icon icon-box-success">
                  <span class="mdi mdi-cart icon-item"></span>
                </div>
              </div>
                <div class="d-flex align-items-center align-self-start col-9 my-auto">
                  <h3 class="mb-0" style="font-size: 1.6rem;font-weight:500">899</h3>
                </div>
            </div>
            <h6 class="text-muted font-weight-normal">Total Order</h6>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex flex-row justify-content-between">
              <h4 class="card-title mb-1">New Product</h4>
              <p class="text-muted mb-1"></p>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="preview-list">
                  {{-- @foreach ($product_new as $item)
                  <div class="preview-item border-bottom">
                    <div class="preview-thumbnail">
                      <div class="preview-icon bg-primary">
                        <i class="mdi mdi-package-variant-closed"></i>
                      </div>
                    </div>
                    <div class="preview-item-content d-sm-flex flex-grow">
                      <div class="flex-grow">
                        <h6 class="preview-subject">{{ $item->product_name }}</h6>
                        <p class="text-muted mb-0">{{ $item->category->category_name }}</p>
                      </div>
                      <div class="mr-auto text-sm-right pt-2 pt-sm-0">
                        <p class="text-muted">Rp. {{ number_format($item->price, 0, ',', '.') }} </p>
                        <p class="text-muted mb-0">{{ $item->created_at->diffForHumans() }}</p>
                      </div>
                    </div>
                  </div>
                  @endforeach --}}


                  {{-- <div class="preview-item border-bottom">
                    <div class="preview-thumbnail">
                      <div class="preview-icon bg-success">
                        <i class="mdi mdi-cloud-download"></i>
                      </div>
                    </div>
                    <div class="preview-item-content d-sm-flex flex-grow">
                      <div class="flex-grow">
                        <h6 class="preview-subject">Wordpress Development</h6>
                        <p class="text-muted mb-0">Upload new design</p>
                      </div>
                      <div class="mr-auto text-sm-right pt-2 pt-sm-0">
                        <p class="text-muted">1 hour ago</p>
                        <p class="text-muted mb-0">23 tasks, 5 issues </p>
                      </div>
                    </div>
                  </div>
                  <div class="preview-item border-bottom">
                    <div class="preview-thumbnail">
                      <div class="preview-icon bg-info">
                        <i class="mdi mdi-clock"></i>
                      </div>
                    </div>
                    <div class="preview-item-content d-sm-flex flex-grow">
                      <div class="flex-grow">
                        <h6 class="preview-subject">Project meeting</h6>
                        <p class="text-muted mb-0">New project discussion</p>
                      </div>
                      <div class="mr-auto text-sm-right pt-2 pt-sm-0">
                        <p class="text-muted">35 minutes ago</p>
                        <p class="text-muted mb-0">15 tasks, 2 issues</p>
                      </div>
                    </div>
                  </div>
                  <div class="preview-item border-bottom">
                    <div class="preview-thumbnail">
                      <div class="preview-icon bg-danger">
                        <i class="mdi mdi-email-open"></i>
                      </div>
                    </div>
                    <div class="preview-item-content d-sm-flex flex-grow">
                      <div class="flex-grow">
                        <h6 class="preview-subject">Broadcast Mail</h6>
                        <p class="text-muted mb-0">Sent release details to team</p>
                      </div>
                      <div class="mr-auto text-sm-right pt-2 pt-sm-0">
                        <p class="text-muted">55 minutes ago</p>
                        <p class="text-muted mb-0">35 tasks, 7 issues </p>
                      </div>
                    </div>
                  </div>
                  <div class="preview-item">
                    <div class="preview-thumbnail">
                      <div class="preview-icon bg-warning">
                        <i class="mdi mdi-chart-pie"></i>
                      </div>
                    </div>
                    <div class="preview-item-content d-sm-flex flex-grow">
                      <div class="flex-grow">
                        <h6 class="preview-subject">UI Design</h6>
                        <p class="text-muted mb-0">New application planning</p>
                      </div>
                      <div class="mr-auto text-sm-right pt-2 pt-sm-0">
                        <p class="text-muted">50 minutes ago</p>
                        <p class="text-muted mb-0">27 tasks, 4 issues </p>
                      </div>
                    </div>
                  </div> --}}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
