@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="card">
                    <div class="card-header">
                      <i class="fa fa-align-justify"></i>{{ __('User Activities') }}</div>
                    <div class="card-body">
                        <table class="table table-responsive-sm table-striped">
                        <thead>
                          <tr>
                            <th>Id</th>
                            <th>User Id</th>
                            <th>Activity Id</th>
                            <th>Day Id</th>
                            <th>Total Hours</th>
                            <th>Created At</th>

                          </tr>
                        </thead>
                        <tbody>
                          @foreach($userlist as $user)
                            <tr>
                              <td>{{ $user->id }}</td>
                              <td>{{ $user->user_id }}</td>
                              <td>{{ $user->activity_id }}</td>
                              <td>{{ $user->day_id }}</td>
                              <td>{{ $user->total_hours }}</td>
                              <td>{{ $user->created_at }}</td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>

@endsection


@section('javascript')

@endsection

