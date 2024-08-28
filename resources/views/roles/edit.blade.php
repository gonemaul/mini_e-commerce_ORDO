@extends('layout.main')

@section('content')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/1b48e60650.js" crossorigin="anonymous"></script>
<ul class="notifications"></ul>

<link rel="stylesheet" href="{{ asset('assets/css/alert.css') }}">
<script src="{{ asset('assets/js/alert.js') }}"></script>
@if(session()->has('success'))
    <script>
        var message = "{{ session()->get('success') }}";
        createToast('success', message);
    </script>
@elseif(session()->has('error'))
    <script>
        var message = "{{ session()->get('error') }}";
        createToast('error', message);
    </script>
@endif
<div class="content-wrapper">
    <div class="p-4" style="background-color: #191c24;border-radius:0.5rem">
        <div class="d-flex justify-content-between mb-3">
            <h3 class="my-auto">{{ __('roles.title.edit') }}</h3>
        </div>
        <form action="{{ route('roles.update', $role->id) }}" method="post">
            @method('PUT')
            @csrf
            @include('roles.partials.item_form')
            <button class="btn btn-primary mt-3" type="submit">{{ __('general.update') }}</button>
            <a href="{{ route('roles.show', $role->id) }}" class="btn btn-secondary mt-3">{{ __('general.cancel') }}</a>
        </form>
    </div>
</div>
@endsection
