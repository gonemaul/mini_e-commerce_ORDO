@extends('layout.main')

@section('content')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/5f3bb645d9.js" crossorigin="anonymous"></script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
<style>
    .card-title{
        padding-bottom: 8px
    }
</style>
<ul class="notifications"></ul>

<link rel="stylesheet" href="{{ asset('assets/css/alert.css') }}">
<script src="{{ asset('assets/js/alert.js') }}"></script>
@if(session()->has('success'))
    <input type="hidden" id="myElement" message="{{ session('success') }}">
    <script>
        var element = document.getElementById('myElement');
        var message = element.getAttribute('message');
        createToast('success', message);
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
                  <h3 class="mb-0" style="font-size: 1.6rem;font-weight:500">{{ count($users) }}</h3>
                </div>
            </div>
            <h6 class="text-muted font-weight-normal">{{ __('dashboard.total_user') }}</h6>
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
                  <h3 class="mb-0" style="font-size: 1.6rem;font-weight:500">{{ count($categories) }}</h3>
                </div>
            </div>
            <h6 class="text-muted font-weight-normal">{{ __('dashboard.total_category') }}</h6>
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
                  <h3 class="mb-0" style="font-size: 1.6rem;font-weight:500">{{ count($products) }}</h3>
                </div>
            </div>
            <h6 class="text-muted font-weight-normal">{{ __('dashboard.total_product') }}</h6>
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
                  <h3 class="mb-0" style="font-size: 1.6rem;font-weight:500">{{ count($orders) }}</h3>
                </div>
            </div>
            <h6 class="text-muted font-weight-normal">{{ __('dashboard.total_order') }}</h6>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex flex-row justify-content-between">
                <h4 class="card-title mb-1 text-primary" style="border-bottom: 3.5px solid #0090e7;">{{ __('dashboard.new_user') }}</h4>
            </div>
                <div class="row">
                    <div class="col-12">
                        <div class="preview-list">
                            @foreach ($users as $index => $user)
                                @if ($index < 5)
                                    <div class="preview-item border-bottom">
                                    <div class="preview-thumbnail">
                                        @if($user->profile_image)
                                            <img class="rounded-circle" style="width: 50px; height: 50px" src="{{ asset('storage/' . $user->profile_image) }}">
                                        @else
                                            <img class="rounded-circle" style="width: 50px; height: 50px" src="https://ui-avatars.com/api/?name={{ $user->name }}&color=7F9CF5&background=EBF4FF">
                                        @endif
                                    </div>
                                    <div class="preview-item-content d-sm-flex flex-grow">
                                        <div class="flex-grow">
                                            <h6 class="preview-subject">{{ $user->name }}</h6>
                                            <p class="text-muted mb-2">{{ $user->email }}</p>
                                            <p class="text-muted">{{ __('general.join') }} {{ $user->created_at->diffForHumans() }}</p>
                                        </div>
                                        <div class="mr-auto text-sm-right pt-2 pt-sm-0 align-content-center">
                                            <label class="badge {{ $user->is_admin ? 'badge-outline-primary' : 'badge-outline-warning' }} ">{{ $user->is_admin ? 'Admin' : 'User' }}</label>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
              <div class="d-flex flex-row justify-content-between">
                  <h4 class="card-title mb-1 text-success" style="border-bottom: 3.5px solid #00d25b;">{{ __('dashboard.new_product') }}</h4>
                  <p class="text-muted mb-1"></p>
              </div>
                  <div class="row">
                      <div class="col-12">
                          <div class="preview-list">
                                @forelse ($products->sortByDesc('created_at') as $index => $product)
                                    @if ($index < 5)
                                        <div class="preview-item border-bottom">
                                        <div class="preview-thumbnail">
                                            @if($product->productImage->isNotEmpty())
                                                <img class="rounded" style="width: 50px; height: 50px" src="{{ asset('storage/' . $product->productImage->first()->path) }}">
                                            @else
                                                <img class="rounded" style="width: 50px; height: 50px" src="{{ asset('assets/images/no-image.png') }}">
                                            @endif
                                        </div>
                                        <div class="preview-item-content d-sm-flex flex-grow">
                                            <div class="flex-grow">
                                                <h6 class="preview-subject">{{ $product->name }}</h6>
                                                <p class="text-muted mb-0">{{ $product->category->name }}</p>
                                            </div>
                                            <div class="mr-auto text-sm-right pt-2 pt-sm-0 align-content-end">
                                                <p class="text-muted mb-0">{{ __('general.added') }} {{ $product->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @empty
                                    <h4 class="text-center mt-4 text-muted">{{ __('general.no_data') }}</h4>
                                @endforelse
                          </div>
                      </div>
                  </div>
            </div>
        </div>
      </div>
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
              <div class="d-flex flex-row justify-content-between">
                  <h4 class="card-title mb-1 text-info" style="border-bottom: 3.5px solid #8f5fe8;">{{ __('dashboard.best_seller') }}</h4>
                  <p class="text-muted mb-1"></p>
              </div>
                  <div class="row">
                      <div class="col-12">
                          <div class="preview-list">
                                @forelse ($products->sortByDesc('sold') as $index => $product)
                                    @if ($index < 5)
                                        <div class="preview-item border-bottom">
                                        <div class="preview-thumbnail">
                                            @if($product->productImage->isNotEmpty())
                                                <img class="rounded" style="width: 50px; height: 50px" src="{{ asset('storage/' . $product->productImage->first()->path) }}">
                                            @else
                                                <img class="rounded" style="width: 50px; height: 50px" src="{{ asset('assets/images/no-image.png') }}">
                                            @endif
                                        </div>
                                        <div class="preview-item-content d-sm-flex flex-grow">
                                            <div class="flex-grow">
                                                <h6 class="preview-subject">{{ $product->name }}</h6>
                                                <p class="text-muted mb-0">{{ $product->category->name }}</p>
                                            </div>
                                            <div class="mr-auto text-sm-right pt-2 pt-sm-0 align-content-end">
                                                <p class="text-muted mb-0">{{ __('general.sold') }} {{ $product->sold }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @empty
                                    <h4 class="text-center mt-4 text-muted">{{ __('general.no_data') }}</h4>
                                @endforelse
                          </div>
                      </div>
                  </div>
            </div>
        </div>
      </div>
      <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
              <div class="d-flex flex-row justify-content-between">
                  <h4 class="card-title mb-1 text-warning" style="border-bottom: 3.5px solid #ffab00;">{{ __('dashboard.new_category') }}</h4>
                  <p class="text-muted mb-1"></p>
              </div>
                  <div class="row">
                      <div class="col-12">
                          <div class="preview-list">
                                @forelse ($categories as $index => $category)
                                    @if ($index < 5)
                                        <div class="preview-item border-bottom">
                                        <div class="preview-thumbnail">
                                            <img class="rounded" style="width: 50px; height: 50px" src="https://ui-avatars.com/api/?name={{ $category->name }}&color=7F9CF5&background=EBF4FF">
                                        </div>
                                        <div class="preview-item-content d-sm-flex flex-grow">
                                            <div class="flex-grow">
                                                <h6 class="preview-subject">{{ $category->name }}</h6>
                                                <p class="text-muted mb-0">Products {{ count($category->products) }}</p>
                                            </div>
                                            <div class="mr-auto text-sm-right pt-2 pt-sm-0 align-content-end">
                                                <p class="text-muted mb-0">{{ __('general.added') }} {{ $category->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @empty
                                  <h4 class="text-center mt-4 text-muted">{{ __('general.no_data') }}</h4>
                                @endforelse
                          </div>
                      </div>
                  </div>
            </div>
        </div>
      </div>
      <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
              <div class="d-flex flex-row justify-content-between">
                  <h4 class="card-title mb-1 text-danger" style="border-bottom: 3.5px solid #fc424a;">{{ __('dashboard.new_order') }}</h4>
                  <p class="text-muted mb-1"></p>
              </div>
                  <div class="row">
                      <div class="col-12">
                          <div class="preview-list">
                                @forelse ($orders as $index => $order)
                                    @if ($index < 5)
                                        <div class="preview-item border-bottom">
                                            <div class="preview-item-content d-sm-flex flex-grow">
                                                <div class="flex-grow">
                                                    <h6 class="preview-subject">{{ $order->name }}</h6>
                                                    <p class="text-muted mb-2">Total :</p>
                                                    <p class="text-muted mb-0">Rp. {{ number_format($order->total, 0, ',', '.') }}</p>
                                                </div>
                                                <div class="mr-auto text-sm-right pt-2 pt-sm-0 align-content-end">
                                                    <p class="text-muted">{{ $order->status }}</p>
                                                    <p class="text-muted mb-0"># {{ $order->order_id }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @empty
                                  <h4 class="text-center mt-4 text-muted">{{ __('general.no_data') }}</h4>
                                @endforelse
                          </div>
                      </div>
                  </div>
            </div>
        </div>
      </div>
    </div>
</div>



@endsection
