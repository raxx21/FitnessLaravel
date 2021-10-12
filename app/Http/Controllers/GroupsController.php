<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //index
    public function index(){
        // $you = auth()->user();
        $userlist = Group::all();
        return view('dashboard.users.groups.grouplist', compact('userlist'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Group::find($id);
        return view('dashboard.users.groups.groupshow', compact( 'user' ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Group::find($id);
        return view('dashboard.users.groups.groupedit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $user = Group::find($id);
        $user->group_name       = $request->input('groupname');
        $user->goal      = $request->input('goal');
        $user->active_members      = $request->input('activemember');
        $user->max_group_members      = $request->input('maxgroupmember');
        $user->location      = $request->input('location');
        $user->group_image      = $request->input('groupimage');

        $user->save();
        $request->session()->flash('message', 'Successfully updated user');
        return redirect()->route('groups.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Group::find($id);
        if($user){
            $user->delete();
        }
        return redirect()->route('groups.index');
    }

}
