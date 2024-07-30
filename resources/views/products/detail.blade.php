@extends('layout.main')

@section('content')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/1b48e60650.js" crossorigin="anonymous"></script>

<link rel="stylesheet" href="{{ asset('assets/vendors/owl-carousel-2/owl.carousel.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/owl-carousel-2/owl.theme.default.min.css') }}">
<style>
    .owl-carousel.portfolio-carousel.full-width .owl-nav{
        bottom: 0;
        text-align: center;
    }
</style>

    <div class="content-wrapper">
        <div class="p-4 row" style="background-color: #191c24;border-radius:0.5rem">
            <div class="col-md-6 col-xl-4 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="owl-carousel owl-theme full-width owl-carousel-dash portfolio-carousel" id="owl-carousel-basic">
                        @foreach ($product->productImage as $img)
                            <div class="item">
                            <img src="{{ asset('storage/' . $img->image) }}" alt="">
                            </div>
                        @endforeach
                    </div>
                  </div>
                </div>
            </div>
            <div class="col-md-6 p-3 pt-4 pl-4">
                <h3 class="mb-4">{{ $product->name }}</h3>
                <div class="row mb-1">
                    <strong class="col-sm-3">Price</strong>
                    <span class="col-sm-9">: {{ $product->price }}</span>
                </div>
                <div class="row mb-1">
                    <strong class="col-sm-3">Stock</strong>
                    <span class="col-sm-9">: {{ $product->stock }}</span>
                </div>
                <div class="row mb-1">
                    <strong class="col-sm-3">Category</strong>
                    <span class="col-sm-9">: {{ $product->category->name }}</span>
                </div>
                <div class="row mb-1">
                    <strong class="col-sm-3">Description</strong>
                    <span class="col-sm-9">: </span>
                    <span class="ml-5">{!! $product->description !!}</span>
                </div>
                <a href="{{ route('products.index') }}" class="btn btn-primary mt-2">Kembali</a>
            </div>
        </div>
    </div>

<script src="{{ asset('assets/vendors/owl-carousel-2/owl.carousel.min.js') }}"></script>
<script>
    if ($('#owl-carousel-basic').length) {
      $('#owl-carousel-basic').owlCarousel({
        loop: true,
        margin: 10,
        dots: false,
        nav: true,
        autoplay: true,
        autoplayTimeout: 4500,
        navText: ["<i class='mdi mdi-chevron-left'></i>", "<i class='mdi mdi-chevron-right'></i>"],
        responsive: {
          0: {
            items: 1
          },
          600: {
            items: 1
          },
          1000: {
            items: 1
          }
        }
      });
    }
</script>
@endsection
