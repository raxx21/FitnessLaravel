@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-6 col-md-5 col-lg-4 col-xl-4">
                <div class="card">
                    <div class="card-header">
                      <i class="fa fa-align-justify"></i> Group {{ $user->group_name }}</div>
                    <div class="card-body">
                        <h4>ID: {{ $user->id }}</h4>
                        <h4>User Id: {{ $user->user_id }}</h4>
                        <h4>Group Name: {{ $user->group_name }}</h4>
                        <h4>Goal: {{ $user->goal }}</h4>
                        <h4>Active Member: {{ $user->active_members }}</h4>
                        <h4>Max Group Member: {{ $user->max_group_members }}</h4>
                        <h4>Location: {{ $user->location }}</h4>
                        <h4>Radius: {{ $user->radius }}</h4>
                        <h4>Group Image: {{ $user->date_of_birth }}</h4>
                        <h4>Status: {{ $user->status }}</h4>
                        <h4>Created: {{ $user->created_at }}</h4>
                        <h4>Updated: {{ $user->updated_at }}</h4>
                        <a href="{{ route('groups.index') }}" class="btn btn-block btn-primary">{{ __('Return') }}</a>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>

@endsection


@section('javascript')

@endsection
