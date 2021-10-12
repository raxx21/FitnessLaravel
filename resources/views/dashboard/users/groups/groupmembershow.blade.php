@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-6 col-md-5 col-lg-4 col-xl-4">
                <div class="card">
                    <div class="card-header">
                      <i class="fa fa-align-justify"></i> User {{ $user->user_name }}</div>
                    <div class="card-body">
                        <h4>Name: {{ $user->user_name }}</h4>
                        <h4>Id Proof: {{ $user->id_proof }}</h4>
                        <h4>E-mail: {{ $user->email }}</h4>
                        <h4>Profile Picture: {{ $user->profile_picture }}</h4>
                        <h4>Personal Goal: {{ $user->personal_goal }}</h4>
                        <h4>Height: {{ $user->height }}</h4>
                        <h4>Weight: {{ $user->weight }}</h4>
                        <h4>Gender: {{ $user->gender }}</h4>
                        <h4>Date Of Birth: {{ $user->date_of_birth }}</h4>
                        <h4>Goal Id: {{ $user->goal_id }}</h4>
                        <h4>Goal Description: {{ $user->goal_description }}</h4>
                        <h4>Status: {{ $user->status }}</h4>
                        <h4>Created: {{ $user->created_at }}</h4>
                        <h4>Updated: {{ $user->updated_at }}</h4>
                        <a href="{{ route('groupmember.index') }}" class="btn btn-block btn-primary">{{ __('Return') }}</a>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>

@endsection


@section('javascript')

@endsection
