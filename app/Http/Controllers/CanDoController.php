<?php

namespace App\Http\Controllers;

use App\Models\Cando;
use App\Models\Userlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isEmpty;

class CanDoController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //index
    public function index(){
        // $you = auth()->user();
        $userlist = Cando::all();
        return view('dashboard.users.can.cando', compact('userlist'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Cando::find($id);
        return view('dashboard.users.can.candoedit', compact('user'));
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

        $user = Cando::find($id);
        $user->can_do       = $request->input('cando');

        $user->save();
        $request->session()->flash('message', 'Successfully updated user');
        return redirect()->route('cando.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Cando::find($id);
        if($user){
            $user->delete();
        }
        return redirect()->route('cando.index');
    }

    public function createCando(Request $request){
        $rules = array(
            "user_id" => "required",
            "can_do" => "required",
        );
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),401);
        }else{
            if(Userlist::where('id',$request->user_id)->first()){
                $value = new Cando;
                $value->user_id=$request->user_id;
                $value->can_do=$request->can_do;
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

    public function Cando($userid){
        if (Userlist::where('id', $userid)->first()) {
            $value = Cando::where('user_id', $userid)->get();
            if(Cando::where('user_id', $userid)->first()){
                return response()->json([
                    "status" => 200,
                    "data" => $value
                ],200);
            }else{
                return response()->json([
                    "status" => 200,
                    "message" => "This Users Cando is Empty"
                ],200);
            }
        }else{
            return response()->json([
                "status" => 400,
                "message" => "User not exists"
            ],400);
        }
    }

    public function deleteCando($id){
        if (Cando::where('id', $id)->first()) {
            $value = Cando::find($id);
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
                "message" => "Cando doesn't exists"
            ],400);
        }
    }

}
