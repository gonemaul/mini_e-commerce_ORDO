<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
<table class="display hover row-border" id="tabel">
    <thead>
      <tr>
        <th class="text-center" style="font-weight:600;"> No </th>
        <th class="text-center" style="font-weight:600;"> Category Name </th>
        <th class="text-center" style="font-weight:600;"> Total Product </th>
        <th class="text-center" style="font-weight:600;"> Action </th>
      </tr>
    </thead>
      <tbody>
          @foreach ($category as $item)
              <tr>
                  <td class="text-center"> {{ $category->firstItem() + $loop->index }} </td>
                  <td> {{ $item->name }} </td>
                  <td class="text-center"> {{ count($item->products) }} </td>
                  <td class="justify-content-center d-flex">
                      <a href="{{ route('categories.edit', $item->id) }}" class="btn btn-outline-warning" style="margin-right: 0.5rem;font-size:1rem"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                      <form action="{{ route('categories.destroy', $item->id) }}" method="post">
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
        $('#tabel').DataTable();
    })
</script>
