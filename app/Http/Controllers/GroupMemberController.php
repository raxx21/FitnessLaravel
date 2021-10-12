<?php

namespace App\Http\Controllers;

use App\Models\GroupMember;
use App\Models\Userlist;
use Illuminate\Http\Request;

class GroupMemberController extends Controller
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
        $userlist = GroupMember::all();
        return view('dashboard.users.groups.groupmemberlist', compact('userlist'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Userlist::find($id);
        return view('dashboard.users.groups.groupmembershow', compact( 'user' ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = GroupMember::find($id);
        if($user){
            $user->delete();
        }
        return redirect()->route('groupmember.index');
    }

}
