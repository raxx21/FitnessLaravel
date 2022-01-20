<?php

namespace App\Http\Controllers;

use App\Models\Prefrence;
use App\Models\Userlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PrefrenceController extends Controller
{
    public function createPrefrence(Request $request){
        $rules = array(
            "day" => "required",
            "user_id" => "required",
            "from_time" => "required",
            "to_time" => "required"
        );
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }else{
            if(Userlist::where('id',$request->user_id)->first()){
                if(Prefrence::where([
                    ['user_id',$request->user_id],
                    ['day',$request->day],
                    ['from_time',$request->from_time],
                    ['to_time',$request->to_time]
                ])->first()){
                    return response()->json([
                        "status" => 404,
                        "message"=>"Prefrence already exists",
                    ],404);

                }else{
                    $value = new Prefrence;
                    $value->day=$request->day;
                    $value->user_id=$request->user_id;
                    $value->from_time=$request->from_time;
                    $value->to_time=$request->to_time;
                    $result = $value->save();
                    if($result){
                        return  response()->json([
                            "status" => 200,
                            "message"=>"success",
                        ],200);
                    }
                    else{
                        return response()->json([
                            "status" => 404,
                            "message"=>"Something went wrong",
                        ],404);
                    }
                }

            }else{
                return response()->json([
                    "status" => 404,
                    "message"=>"User not exists",
                ],404);
            }

        }
    }

    public function prefrence($userId){
        $user = UserList::find($userId);
        if($user){
            $prefrence = Prefrence::where('user_id',$userId)->get();
            if(Prefrence::where('user_id',$userId)->first()){
                return response()->json([
                    "status" => 200,
                    "data"=>$prefrence,
                    "message"=>"success",
                ],200);
            }else{
                return response()->json([
                    "status" => 404,
                    "message"=>"User prefrence are empty",
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
