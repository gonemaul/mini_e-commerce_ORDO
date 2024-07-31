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
    .images img{
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

    <div class="content-wrapper">
        <div class="p-4 row" style="background-color: #191c24;border-radius:0.5rem">
            <div class="col-md-6 col-xl-4 stretch-card">
                <div class="card">
                  <div class="card-body p-0">
                    <div class="owl-carousel owl-theme full-width owl-carousel-dash portfolio-carousel" id="owl-carousel-basic">
                        @foreach ($product->productImage as $img)
                            <div class="item images">
                                <img src="{{ asset('storage/' . $img->image) }}" alt="">
                            </div>
                        @endforeach
                    </div>
                  </div>
                </div>
            </div>
            <div class="col-md-6 p-3 pt-4 pl-4">
                <h3 class="mb-1">{{ $product->name }}</h3>
                <div class="row mb-3">
                    <span class="col-sm-9"><label class="badge badge-outline-success">{{ $product->category->name }}</label></span>
                </div>
                <div class="row mb-1">
                    <h4 class="col-sm-9">Rp. {{ $product->price }}</h4>
                </div>
                <div class="row mb-1">
                    <span class="col-sm-9 text-muted" style="font-size: 15px">Tersisa {{ $product->stock }}</span>
                </div>
                <div class="row mb-1">
                    <span class="col-sm-9 text-muted" style="font-size: 15px">Terjual {{ $product->terjual }}</span>
                </div>
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary mt-2">Back</a>
            </div>
            <div class="col-md-6 p-3 pt-4 pl-4">
                <h4 class="text-bold">Description</h4>
                <div class="card">
                    <article>{!! $product->description !!}</article>
                </div>
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
