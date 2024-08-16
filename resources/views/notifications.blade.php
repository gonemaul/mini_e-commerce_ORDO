@extends('layout.main')

@section('content')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/1b48e60650.js" crossorigin="anonymous"></script>
<style>
    .item{
        border-radius: 5px;
        align-items: center;
        .preview-icon i{
            font-size: 1.5rem;
        }
    }
    .item.before{
        background-color: #000000;
        opacity: 1;
    }
    .item.after{
        background-color: #000000;
        opacity: 0.4;
        .read{
            display: none;
        }
    }
</style>
<link rel="stylesheet" href="{{ asset('assets/css/alert.css') }}">
<ul class="notifications mt-3"></ul>
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
    <div class="col-lg-12 p-4" style="background-color: #191c24;border-radius:0.5rem">
        <div class="d-flex justify-content-between mb-3">
            <h3 class="my-auto">Notifications</h3>
            <div class="action-group">
                <a href="{{ route('notifications.readAll') }}" class="btn btn-outline-primary mr-2" onclick="return confirm('What are you sure? ..');"><i class="fa-solid fa-envelope-open-text"></i>Read All</a>
                <a href="{{ route('notifications.removeAll') }}" class="btn btn-outline-danger" onclick="return confirm('What are you sure? ..');"><i class="fa-solid fa-trash-can"></i>Remove All</a>
            </div>
        </div>
        @forelse ($notifications as $notification)
            <div class="item mb-2 d-flex {{ $notification->read_at == null ? 'before' : 'after' }}">
                <div class="preview-icon p-3 pl-4 text-center">
                    <i class="mdi mdi-"></i>
                </div>
                <div class="preview-item-content pl-3 py-2">
                    <a href="{{ route('notifications.detail' , $notification->id) }}" style="text-decoration: none;color:transparent">
                        <p class="mb-1 mt-1 text-white" style="font-size: 1.2rem">{{ $notification->data['title'] }}</p>
                        <p class="text-muted mb-1">{{ $notification->data['message'] }}</p>
                        <p class="text-muted mb-0">{{ $notification->created_at->diffForHumans() }}</p>
                    </a>
                </div>
                <div class="ml-auto d-flex align-items-center">
                    <a href="{{ route('notifications.markAsRead' , $notification->id) }}" class="mr-2 read" style="font-size: 1rem">
                        <i class="fa-solid fa-envelope-open-text"></i>
                    </a>
                    <form action="{{ route('notifications.remove', $notification->id) }}" method="post" class="mr-4">
                        @csrf
                        <button type="submit" class="btn text-danger" style="font-size: 1.5rem" onclick="return confirm('What are you sure? ..');">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center">No notification available...</div>
        @endforelse
    </div>
</div>
@endsection
