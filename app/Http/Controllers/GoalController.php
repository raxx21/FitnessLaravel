<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\Userlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class GoalController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //index
    public function index(){
        // $you = auth()->user();
        $userlist = Goal::all();
        return view('dashboard.users.goal.goallist', compact('userlist'));
    }

    public function createGoal(Request $request){
        $rules = array(
            "user_id" => "required",
            "goal" => "required",
        );
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),401);
        }else{
            if(Userlist::where('id',$request->user_id)->first()){
                $value = new Goal;
                $value->user_id=$request->user_id;
                $value->goal=$request->goal;
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
                    "message"=>"User not exists",
                ],400);
            }

        }
    }

    public function goal($userid){
        if (Userlist::where('id', $userid)->first()) {
            $value = Goal::where('user_id', $userid)->get();
            if(Goal::where('user_id', $userid)->first()){
                return response()->json([
                    "status" => 200,
                    "data" => $value
                ],200);
            }else{
                return response()->json([
                    "status" => 200,
                    "message" => "This Users Goal is Empty"
                ],200);
            }
        }else{
            return response()->json([
                "status" => 400,
                "message" => "User not exists"
            ],400);
        }
    }

    public function deleteGoal($id){
        if (Goal::where('id', $id)->first()) {
            $value = Goal::find($id);
            if($value){
                $value->delete();
                return response()->json([
                    "status" => 200,
                    "data" => "Successfully Deleted"
                ],200);
            }
            else{
                return response()->json([
                    "status" => 400,
                    "message" => "Something went wrong"
                ],400);
            }
        }else{
            return response()->json([
                "status" => 400,
                "message" => "Goal doesn't exists"
            ],400);
        }
    }
}
