@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-6 col-md-5 col-lg-4 col-xl-4">
                <div class="card">
                    <div class="card-header">
                      <i class="fa fa-align-justify"></i> {{ __('Edit') }} {{ $user->group_name }}</div>
                    <div class="card-body">
                        <br>
                        <form method="POST" action="/groups/{{ $user->id }}">
                            @csrf
                            @method('PUT')
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                      Name
                                    </span>
                                </div>
                                <input class="form-control" type="text" placeholder="{{ __('Name') }}" name="groupname" value="{{ $user->group_name }}" required autofocus>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Goal</span>
                                </div>
                                <input class="form-control" type="text" placeholder="{{ __('Goal') }}" name="goal" value="{{ $user->goal }}" required>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Active member</span>
                                </div>
                                <input class="form-control" type="text" placeholder="{{ __('Active Member') }}" name="activemember" value="{{ $user->active_members }}" required>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Max Group Members</span>
                                </div>
                                <input class="form-control" type="text" placeholder="{{ __('Max Group Members') }}" name="maxgroupmember" value="{{ $user->max_group_members }}" required>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Location</span>
                                </div>
                                <input class="form-control" type="text" placeholder="{{ __('Location') }}" name="location" value="{{ $user->location }}" required>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Group Image</span>
                                </div>
                                <input class="form-control" type="text" placeholder="{{ __('Group Image') }}" name="groupimage" value="{{ $user->group_image }}" required>
                            </div>
                            <button class="btn btn-block btn-success" type="submit">{{ __('Save') }}</button>
                            <a href="{{ route('groups.index') }}" class="btn btn-block btn-primary">{{ __('Return') }}</a>
                        </form>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>

@endsection

@section('javascript')

@endsection
