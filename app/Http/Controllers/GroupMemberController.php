<?php

namespace App\Http\Controllers;

use App\Models\GroupMember;
use App\Models\Group;
use App\Models\Userlist;
use Illuminate\Http\Request;

class GroupMemberController extends Controller
{
    // /**
    //  * Create a new controller instance.
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware('admin');
    // }

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

    // Api functions
    public function getGroupMember($id){
        if(GroupMember::where('group_id',$id)->first()){
            $group_member= GroupMember::where('group_id',$id)->get();
            if($group_member){
                $data  = $group_member->map(function ($e){
                    $element['user_id'] = UserList::find($e['user_id']);
                    return $element;
                });
                return response()-> json([
                    "status" => 200,
                    "data" => $data
                ],200);
            }
            else{
                return response()-> json([
                    "status" => 404,
                    "message" => "Group Doesn't exists"
                ],404);
            }
        }
        else{
            return response()-> json([
                "status" => 404,
                "message" => "Group Doesn't exists"
            ],404);
        }
    }

}
