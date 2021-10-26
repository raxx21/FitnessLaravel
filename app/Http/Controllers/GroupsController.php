<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupMember;
use App\Models\Userlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupsController extends Controller
{


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

    // Apis section
    public function createGroup($id,Request $request){

        $user = Userlist::find($id);
        if($user){
            $rules = array(
                "group_name" => "required",
                "goal" => "required",
                "max_group_members" => "required",
                "location" => "required",
                "comments" => "required"
            );
            $validator = Validator::make($request->all(),$rules);
            if($validator->fails()){
                return response()->json($validator->errors(),401);
            }else{
                $group = new Group;
                $group->user_id=$id;
                $group->group_name=$request->group_name;
                $group->goal=$request->goal;
                $group->active_members=1;
                $group->max_group_members=$request->max_group_members;
                $group->location=$request->location;
                $group->comments =$request->comments;
                $group->radius=$request->radius;

                if ($request->hasFile('group_image')) {
                    $image = $request->file('group_image');
                    $name = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('../../public/uploads/images');
                    $image->move($destinationPath, $name);
                    if($group->group_image='/images/'.$name)
                    {
                        $group->group_image='/images/'.$name;
                    }else{
                        return response()->json([
                            "status" => 404,
                            "message" => 'Sorry something went wrong while uploading group image',
                        ],404);
                    }
                }else{
                    $group->group_image='';
                }
                $result=$group->save();

                if($result){
                    $group_member= new GroupMember;
                    $group_member->group_id=$group->id;
                    $group_member->user_id=$id;
                    $group_member->admin=1;
                    $group_member_result = $group_member->save();
                    if($group_member_result){
                        return response()->json([
                            "status" => 200,
                            "data" => [
                                'group' => $group,
                                'user' => $user
                            ]
                        ],200);
                    }else{
                        return response()->json([
                            "status" => 404,
                            "message"=>"Something went wrong",
                        ],404);
                    }
                }
                else{
                    return response()->json([
                        "status" => 404,
                        "message"=>"Something went wrong",
                    ],404);
                }
            }
        }else{
            return response()-> json([
                "status" => 404,
                "message" => "User Doesn't exists"
            ],404);
        }
    }

    public function getMyOwnGroup($id){
        $user = Userlist::find($id);
        if($user){
            if(Group::where('user_id',$id)->first()){
                $group_member= Group::where('user_id',$id)->get();
                return response()->json([
                    'status' => 200,
                    'data' => $group_member
                ],200);

            }else{
            return response()-> json([
                "status" => 404,
                "message" => "User doesn't have group"
            ],404);
        }
        }else{
            return response()-> json([
                "status" => 404,
                "message" => "User Doesn't exists"
            ],404);
        }
    }

    public function joinGroup($id,$groupId){
        $user = UserList::find($id);
        if($user){
            $group = Group::find($groupId);
            if($group){
                $active_members = $group->active_members;
                $max_group_members = $group->max_group_members;
                if($active_members < $max_group_members){
                    if(GroupMember::where('group_id',$groupId)->where('user_id',$id)->first()){
                        return response()-> json([
                            "status" => 404,
                            "message" => "User Already exists in this group"
                        ],404);
                    }else{
                    $insert_group_member = new GroupMember;
                    $insert_group_member->group_id = $groupId;
                    $insert_group_member->user_id = $id;
                    $result = $insert_group_member->save();
                        if($result){
                        $increase_members = $group->active_members + 1;
                        $group->active_members= $increase_members;
                        $result_active_member = $group->save();
                            if($result_active_member){
                                $group_member= GroupMember::where('group_id',$groupId)->get();
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
                            } else{
                                return response()-> json([
                                    "status" => 404,
                                    "message" => "Something went wrong"
                                ],404);
                            }
                        }else{
                            return response()-> json([
                                "status" => 404,
                                "message" => "Something went wrong"
                            ],404);
                        }
                    }
                }else{
                    return response()-> json([
                        "status" => 404,
                        "message" => "Group Is full"
                    ],404);
                }
            }else{
            return response()-> json([
                "status" => 404,
                "message" => "Group Doesn't exists"
            ],404);
            }

        }else{
            return response()-> json([
                "status" => 404,
                "message" => "User Doesn't exists"
            ],404);
        }
    }

    public function getGroupNotAdmin($id){
        $user = UserList::find($id);
        if($user){
            $group = GroupMember::where('user_id',$id)->where('admin',0)->get();
            if(GroupMember::where('user_id',$id)->where('admin',0)->first()){
                $data  = $group->map(function ($e){
                    $element['group_id'] = Group::find($e['group_id']);
                    return $element;
                });
                return response()-> json([
                    "status" => 200,
                    "data" => $data
                ],200);
            }else{
            return response()-> json([
                "status" => 404,
                "message" => "User doesn't have group"
            ],404);
        }
        }else{
            return response()-> json([
                "status" => 404,
                "message" => "User Doesn't exists"
            ],404);
        }
    }

}
