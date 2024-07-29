@extends('layout.main')

@section('content')
<div class="content-wrapper">
  <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th class="text-center" style="font-weight:600;"> Profile </th>
                  <th class="text-center" style="font-weight:600;"> Name </th>
                  <th class="text-center" style="font-weight:600;"> Email </th>
                  <th class="text-center" style="font-weight:600;"> Role </th>
                  <th class="text-center" style="font-weight:600;"> Action </th>
                </tr>
              </thead>
              <tbody>
                @foreach ($users as $item)
                  <tr>
                    <td class="text-center">
                        @if ($item->profile_image)
                            <img src="{{ asset('storage/' . $item->profile_image)}}"> </td>
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ $item->name }}&color=7F9CF5&background=EBF4FF"> </td>
                        @endif
                    <td> {{ $item->name }} </td>
                    <td> {{ $item->email }} </td>
                    <td class="text-center">
                        @if ($item->is_admin)
                            <label class="badge {{ auth()->user()->id == $item->id ? 'badge-primary' : 'badge-outline-primary'}}">Administrator</label>
                        @else
                            <label class="badge badge-outline-warning">User</label>
                        @endif
                    <td class="text-center"><a href="{{ route('users.detail', $item->id) }}" class="btn btn-info" style="font-size:1rem"><i class="mdi mdi-eye"></i>Detail</a></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
  </div>
</div>
@endsection
