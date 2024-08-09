<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
<table id="product_tabel" class="display hover row-border">
    <thead>
      <tr>
        <th class="text-center" style="font-weight:600;"> No </th>
        <th class="text-center" style="font-weight:600;"> Product Name </th>
        <th class="text-center" style="font-weight:600;"> Category </th>
        <th class="text-center" style="font-weight:600;"> Price </th>
        <th class="text-center" style="font-weight:600;"> Stock </th>
        <th class="text-center" style="font-weight:600;"> Action </th>
      </tr>
    </thead>
    <tbody>
      @foreach ($products as $item)
        <tr>
          <td class="text-center"> {{ $loop->iteration }}</td>
          <td> {{ $item->name }} </td>
          <td class="text-center"> {{ $item->category->name }} </td>
          <td class="text-center">Rp. {{ number_format($item->price, 0, ',', '.') }} </td>
          <td class="text-center"> {{ $item->stock }} </td>
          <td class="justify-content-center d-flex">
                <a href="{{ route('products.show', $item->id) }}" class="btn btn-outline-primary" style="margin-right: 0.5rem;font-size:1rem"><i class="fa-solid fa-eye"></i> Detail</a>
                <a href="{{ route('products.edit', $item->id) }}" class="btn btn-outline-warning" style="margin-right: 0.5rem;font-size:1rem"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                <form action="{{ route('products.destroy', $item->id) }}" method="post">
                  @method('delete')
                  @csrf
                  <button type="submit" class="btn btn-outline-danger" onclick="return confirm('What are you sure? ..')" style="font-size:1rem"><i class="fa-solid fa-trash"></i> Delete</button>
                </form>
          </td>
        </tr>
      @endforeach
    </tbody>
</table>
<script src="{{ asset('assets/js/datatables.js') }}"></script>
<script>
    $(document).ready(function(){
        $('#product_tabel').DataTable();
    })
</script>
