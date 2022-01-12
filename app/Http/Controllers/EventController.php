<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activitie;
use App\Models\Event;
use App\Models\Group;
use App\Models\Userlist;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    // Api Function
    public function createEvent(Request $request){
        $rules = array(
            "title" => "required",
            "description" => "required",
            "location" => "required",
            "members" => "required",
            "date" => "required",
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
                    $value = new Event;
                    $value->title=$request->title;
                    $value->description=$request->description;
                    $value->location=$request->location;
                    $value->members=$request->members;
                    $value->date=$request->date;
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
                            "status" => 400,
                            "message"=>"Something went wrong",
                        ],400);
                    }
                }else{
                    return response()->json([
                        "status" => 400,
                        "message"=>"Group not exists",
                    ],400);
                }
            }else{
                return response()->json([
                    "status" => 400,
                    "message"=>"User not exists",
                ],400);
            }

        }
    }

    public function event($id){
        $event = Event::where('group_id',$id)->paginate();
        if(Event::where('group_id',$id)->first()){
            return response()->json([
                "status" => 200,
                "data" => $event
            ],200);
        }
        else{
            return response()->json([
                "status" => 400,
                "message" => "Activity doesn't exists"
            ],400);
        }
    }
}
