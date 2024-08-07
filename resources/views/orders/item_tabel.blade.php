<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
<table id="tabel" class="display row-border hover">
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
                    @switch($item->status)
                        @case('Success')
                            <label class="badge badge-outline-success">{{ $item->status }}</label>
                            @break
                        @case('Pending')
                            <label class="badge badge-outline-warning">{{ $item->status }}</label>
                            @break
                        @case('Failed')
                            <label class="badge badge-outline-danger">{{ $item->status }}</label>
                            @break
                        @case('Expired')
                            <label class="badge badge-outline-info">{{ $item->status }}</label>
                            @break
                        @case('Canceled')
                            <label class="badge badge-outline-danger">{{ $item->status }}</label>
                            @break
                        @default
                            <label class="badge badge-outline-secondary">{{ $item->status }}</label>
                    @endswitch
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
<script src="{{ asset('assets/js/datatables.js') }}"></script>
<script>
    $(document).ready(function(){
        $('#tabel').DataTable();
    })
</script>
