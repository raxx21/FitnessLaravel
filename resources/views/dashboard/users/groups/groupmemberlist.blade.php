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
                            <th>Group ID</th>
                            <th>User ID</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th></th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($userlist as $user)
                            <tr>
                              <td>{{ $user->id }}</td>
                              <td>{{ $user->group_id }}</td>
                              <td>{{ $user->user_id }}</td>
                              <td>{{ $user->status }}</td>
                              <td>{{ $user->created_at }}</td>
                              <td>
                                <a href="{{ url('/groupmember/' . $user->id) }}" class="btn btn-block btn-primary">View</a>
                              </td>
                              <td>
                                <form action="{{ route('groupmember.destroy', $user->id ) }}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button class="btn btn-block btn-danger">Remove Member</button>
                                </form>
                              </td>
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

