<?php

namespace App\Http\Controllers;

use App\Models\Activitie;
use App\Models\Group;
use App\Models\Userlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActivitiesController extends Controller
{
    // index
    public function index()
    {
        // $you = auth()->user();
        $users = Activitie::all();
        return view('dashboard.activities.activities', compact('users'));
    }

    // Api Functions
    public function createActivity(Request $request){
        $rules = array(
            "title" => "required",
            "description" => "required",
            "location" => "required",
            "note" => "required",
            "day" => "required",
            "from_time" => "required",
            "to_time" => "required",
            "user_id" => "required",
            "group_id" => "required",
        );
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }else{
            if(Userlist::where('id',$request->user_id)->first()){
                if(Group::where('id',$request->group_id)->first()){
                    $value = new Activitie;
                    $value->title=$request->title;
                    $value->description=$request->description;
                    $value->location=$request->location;
                    $value->note=$request->note;
                    $value->day=$request->day;
                    $value->from_time=$request->from_time;
                    $value->to_time=$request->to_time;
                    $value->user_id=$request->user_id;
                    $value->group_id=$request->group_id;
                    $result = $value->save();
                    if($result){
                        return  response()->json([
                            "status" => 200,
                            "message"=>"success",
                        ],200);
                    }
                    else{
                        return response()->json([
                            "status" => 40,
                            "message"=>"Something went wrong",
                        ],404);
                    }
                }else{
                    return response()->json([
                        "status" => 404,
                        "message"=>"Group not exists",
                    ],404);
                }
            }else{
                return response()->json([
                    "status" => 404,
                    "message"=>"User not exists",
                ],404);
            }

        }
    }

    public function activity($id){
        $activity = Activitie::where('group_id',$id)->simplePaginate();
        if(Activitie::where('group_id',$id)->first()){
            return response()->json([
                "status" => 200,
                "data" => $activity
            ],200);
        }
        else{
            return response()->json([
                "status" => 404,
                "message" => "Activity doesn't exists"
            ],404);
        }
    }

    public function deleteActivity($id){
        $activity = Activitie::find($id);
        if($activity){
            $activity->delete();
            return response()->json([
                "status" => 200,
                "message" => "Successfully Deleted"
            ],200);
        }
        else{
            return response()->json([
                "status" => 404,
                "message" => "Activity doesn't exists"
            ],404);
        }
    }
}
