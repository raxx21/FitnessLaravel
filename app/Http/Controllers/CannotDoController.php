<?php

namespace App\Http\Controllers;

use App\Models\Cannotdo;
use App\Models\Userlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class CannotDoController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //index
    public function index(){
        // $you = auth()->user();
        $userlist = Cannotdo::all();
        return view('dashboard.users.can.cannotdo', compact('userlist'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Cannotdo::find($id);
        return view('dashboard.users.can.cannotdoedit', compact('user'));
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

        $user = Cannotdo::find($id);
        $user->cannot_do       = $request->input('cannotdo');

        $user->save();
        $request->session()->flash('message', 'Successfully updated user');
        return redirect()->route('cannotdo.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Cannotdo::find($id);
        if($user){
            $user->delete();
        }
        return redirect()->route('cannotdo.index');
    }

    public function createCannotdo($id,Request $request){
        $rules = array(
            "cannot_do" => "required",
        );
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),401);
        }else{
            if(Userlist::where('id',$id)->first()){
                $value = new Cannotdo;
                $value->user_id=$id;
                $value->cannot_do=$request->cannot_do;
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

    public function Cannotdo($userid){
        if (Userlist::where('id', $userid)->first()) {
            $value = Cannotdo::where('user_id', $userid)->get();
            $data  = $value->map(function ($e){
                $element['id'] = $e['id'];
                $element['user_id'] = $e['user_id'];
                $element['text'] = $e['cannot_do'];
                $element['created_at'] = $e['created_at'];
                $element['updated_at'] = $e['updated_at'];
                return $element;
            });
            if(Cannotdo::where('user_id', $userid)->first()){
                return response()->json([
                    "status" => 200,
                    "data" => $data
                ],200);
            }else{
                return response()->json([
                    "status" => 200,
                    "message" => "This Users Cannotdo is Empty"
                ],200);
            }
        }else{
            return response()->json([
                "status" => 400,
                "message" => "User not exists"
            ],400);
        }
    }

    public function deleteCannotdo($id){
        if (Cannotdo::where('id', $id)->first()) {
            $value = Cannotdo::find($id);
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
                "message" => "Cannotdo doesn't exists"
            ],400);
        }
    }
}
