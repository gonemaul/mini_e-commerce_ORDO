@extends('layout.main')

@section('content')
<script src="https://kit.fontawesome.com/1b48e60650.js" crossorigin="anonymous"></script>
<div class="content-wrapper">
    <div class="p-4" style="background-color: #191c24;border-radius:0.5rem">
        <div class="d-flex justify-content-between mb-3">
            <h3 class="my-auto">Orders List</h3>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
            <thead>
                <tr>
                <th class="text-center" style="font-weight:600;"> No </th>
                <th class="text-center" style="font-weight:600;"> User </th>
                <th class="text-center" style="font-weight:600;"> Total </th>
                <th class="text-center" style="font-weight:600;"> Status </th>
                <th class="text-center" style="font-weight:600;"> Action </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration}}</td>
                        <td class="text-center">{{ $item->user->name }}</td>
                        <td class="text-center"> Rp. {{ number_format($item->total, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if ($item->status == 'pending')
                                <label class="badge badge-outline-warning"><i class="fa-solid fa-hourglass-end mr-1"></i> Pending</label>
                            @elseif ($item->status == 'in-progress')
                                <label class="badge badge-outline-primary"><i class="fa-solid fa-truck mr-1"></i> In Proccess</label>
                            @elseif ($item->status == 'success')
                                <label class="badge badge-outline-success"><i class="fa-solid fa-circle-check mr-1"></i> Success</label>
                            @elseif ($item->status == 'cancelled')
                                <label class="badge badge-outline-danger"><i class="fa-solid fa-circle-xmark mr-1"></i> Cancelled</label>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('orders.detail', $item->id) }}" class="btn btn-outline-info"><i class="fa-solid fa-eye"></i> Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No data available.</td>
                    </tr>
                @endforelse
            </tbody>
            </table>
            <div class="d-flex justify-content-end mt-3">
                {{ $orders->links('components.pagination') }}
            </div>
        </div>
    </div>
</div>
@endsection
