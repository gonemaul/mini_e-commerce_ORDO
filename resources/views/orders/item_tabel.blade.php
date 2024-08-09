<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
<table id="tabel" class="display row-border hover">
    <thead>
        <tr>
        <th class="text-center" style="font-weight:600;"> Order ID </th>
        <th class="text-center" style="font-weight:600;"> User </th>
        <th class="text-center" style="font-weight:600;"> Total </th>
        <th class="text-center" style="font-weight:600;"> Status </th>
        <th class="text-center" style="font-weight:600;"> Action </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $item)
            <tr>
                <td class="text-center">#{{ $item->order_id }}</td>
                <td class="text-center">{{ $item->name }}</td>
                <td class="text-center"> Rp. {{ number_format($item->total, 0, ',', '.') }}</td>
                <td class="text-center">
                    @switch($item->status)
                        @case('Success')
                            <label class="badge badge-outline-success"><i class="fa-regular fa-circle-check mr-2"></i>{{ $item->status }}</label>
                            @break
                        @case('Pending')
                            <label class="badge badge-outline-warning"><i class="fa-regular fa-clock mr-2"></i> {{ $item->status }}</label>
                            @break
                        @case('Failed')
                            <label class="badge badge-outline-danger">{{ $item->status }}</label>
                            @break
                        @case('Expired')
                            <label class="badge badge-outline-info">{{ $item->status }}</label>
                            @break
                        @case('Canceled')
                            <label class="badge badge-outline-danger"><i class="fa-solid fa-xmark mr-2"></i>{{ $item->status }}</label>
                            @break
                        @default
                            <label class="badge badge-outline-secondary">{{ $item->status }}</label>
                    @endswitch
                </td>
                <td class="justify-content-center d-flex">
                    <a href="{{ route('orders.detail', $item->id) }}" class="btn btn-outline-primary mr-1"><i class="fa-solid fa-eye"></i> Detail</a>
                    <form action="{{ route('orders.cancel', $item->order_id) }}" method="post">
                        @csrf
                        @if($item->status == 'Pending')
                            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('What are you sure? ..')" style="font-size:1rem"><i class="fa-solid fa-xmark"></i> Cancel</button>
                        @else
                            <button type="submit" disabled class="btn btn-outline-danger" onclick="return confirm('What are you sure? ..')" style="font-size:1rem"><i class="fa-solid fa-xmark"></i> Cancel</button>
                        @endif
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
{{-- <script src="{{ asset('assets/js/datatables.js') }}"></script> --}}
<script src="{{ asset('assets/vendors/DataTables/datatables.js') }}"></script>
<script>
    $(document).ready(function(){
        $('#tabel').DataTable({
            columnDefs: [{
			targets: [0,3,4],
			orderable: false,
		    }],
            "language": {
			"info": "_START_-_END_ of _TOTAL_ entries",
			searchPlaceholder: "Search",
            },
        });
    })
</script>
