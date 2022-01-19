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

    public function createGoal($id,Request $request){
        $rules = array(
            "goal" => "required",
        );
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),401);
        }else{
            if(Userlist::where('id',$id)->first()){
                $value = new Goal;
                $value->user_id=$id;
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
            $data  = $value->map(function ($e){
                $element['id'] = $e['id'];
                $element['user_id'] = $e['user_id'];
                $element['text'] = $e['goal'];
                $element['created_at'] = $e['created_at'];
                $element['updated_at'] = $e['updated_at'];
                return $element;
            });

            if(Goal::where('user_id', $userid)->first()){
                return response()->json([
                    "status" => 200,
                    "data" => $data,
                    "message" => 'success'
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
