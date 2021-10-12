@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="card">
                    <div class="card-header">
                      <i class="fa fa-align-justify"></i>{{ __('Users') }}</div>
                    <div class="card-body">
                        <table class="table table-responsive-sm table-striped">
                        <thead>
                          <tr>
                            <th>Id</th>
                            <th>Goal Name</th>
                            <th>From date</th>
                            <th>To Date</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Updated At</th>

                          </tr>
                        </thead>
                        <tbody>
                          @foreach($userlist as $user)
                            <tr>
                              <td>{{ $user->id }}</td>
                              <td>{{ $user->goal_name }}</td>
                              <td>{{ $user->from_date }}</td>
                              <td>{{ $user->to_date }}</td>
                              <td>{{ $user->description }}</td>
                              <td>{{ $user->status }}</td>
                              <td>{{ $user->created_at }}</td>
                              <td>{{ $user->updated_at }}</td>
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

