<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Userlist;
use Illuminate\Support\Facades\Hash;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Validator;

class UserLists extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
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
        $userlist = Userlist::all();
        return view('dashboard.users.userlist.userlist', compact('userlist'));
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
        return view('dashboard.users.userlist.userlistshow', compact( 'user' ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Userlist::find($id);
        return view('dashboard.users.userlist.userlistedit', compact('user'));
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
        $validatedData = $request->validate([
            'username'       => 'required|min:1|max:256'
        ]);
        $user = Userlist::find($id);
        $user->user_name       = $request->input('username');
        $user->height      = $request->input('height');
        $user->weight      = $request->input('weight');
        $user->gender      = $request->input('gender');
        $user->date_of_birth      = $request->input('dob');
        $user->goal_description      = $request->input('goal');

        $user->save();
        $request->session()->flash('message', 'Successfully updated user');
        return redirect()->route('userlist.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Userlist::find($id);
        if($user){
            $user->delete();
        }
        return redirect()->route('userlist.index');
    }

    // API Functions
    public function getUserListApi()
    {
        $list = Userlist::all();
        return response()->json([
            "result" => $list,
            "count" => count($list),
            "message" => 'Success',
            "status" => 1
        ]);
    }

    // Login
    function login(Request $request)
    {
        $user= Userlist::where('email', $request->email)->first();
        // print_r($data);
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response([
                    'message' => ['These credentials do not match our records.']
                ], 404);
            }

            //  $token = $user->createToken('my-app-token')->plainTextToken;

            $response = [
                'status' => "success",
                'user' => $user,
                // 'token' => $token
            ];

             return response($response, 201);
    }

    //post user
    function register(Request $req){

        $rules = array(
            "user_name" => "required",
            "email" => "required|email|regex:/^[a-zA-Z]{1}/|unique:users,email",
            "password" => "required",
        );
        $validator = Validator::make($req->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),401);
        }
        else{
            if(Userlist::where('email',$req->email)->first()){
                return [
                    "status" => 201,
                    "message"=>"the email has already been taken.",
                ];
            }else{
                $user = new Userlist;
                $options = [
                    'rounds' => 12,
                ];
                // $pass = password_hash($req->password, PASSWORD_DEFAULT, $options);
                $pass = Hash::make($req->password, $options);
                $user->user_name=$req->user_name;
                $user->profile_picture=$req->profile_picture;
                $user->personal_goal=$req->personal_goal;
                $user->height=$req->height;
                $user->weight=$req->weight;
                $user->gender=$req->gender;
                $user->date_of_birth=$req->date_of_birth;
                $user->goal_id=$req->goal_id;
                $user->goal_description=$req->goal_description;
                $user->email=$req->email;
                $user->password=$pass;
                $user->id_proof=$req->id_proof;
                $user->number=$req->number;
                $result =$user->save();
                if($result){
                    return [
                        "status" => 200,
                        "message"=>"success",
                    ];
                }
                else{
                    return response()->json([
                        "status" => 400,
                        "message"=>"Bad Request",
                    ],400);
                }
            }
        }
    }

    //put req for user
    function update_user($id,Request $req){

        if(Userlist::find($id)){
            $user = Userlist::find($id);
            $user->user_name=$req->user_name;
            $user->profile_picture=$req->profile_picture;
            $user->personal_goal=$req->personal_goal;
            $user->height=$req->height;
            $user->weight=$req->weight;
            $user->gender=$req->gender;
            $user->date_of_birth=$req->date_of_birth;
            $user->goal_id=$req->goal_id;
            $user->goal_description=$req->goal_description;
            $user->email=$req->email;
            $user->group_count= $req->group_count;
            $user->id_proof=$req->id_proof;
            $user->number=$req->number;
            $result =$user->save();
            if($result){
                return [
                    "status" => 200,
                    "message"=>"success",
                    "result" => $user
                ];
            }
            else{
                return [
                    "status" => 400,
                    "message"=>"Bad Request",
                ];
            }
        }
        else{
            return [
                "status" => 400,
                "message"=>"User Doesn't exists",
            ];
        }
    }

    //delete user
    function delete_user($id){

        $user = Userlist::find($id);
        if($user){
            $result = $user->delete();
            if($result){
                return [
                    "status" => 200,
                    "message"=>"success",
                ];
            }
            else{
                return [
                    "status" => 400,
                    "message"=>"Bad Request",
                ];
            }
        }
        else{
            return response()->json([
                "status" => 400,
                "message"=>"User not found",
            ],400);
        }
    }

    // Profile update
    public function profile_picture(Request $request){

        $input = $request->all();
        $validator = Validator::make($input, [
            'user_id' => 'required',
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('../../public/uploads/images');
            $image->move($destinationPath, $name);
            if(Userlist::where('id',$input['user_id'])->update([ 'profile_picture' => '/images/'.$name ])){
                return response()->json([
                    "result" => Userlist::select('id', 'user_name','email','profile_picture','status')->where('id',$input['user_id'])->first(),
                    "message" => 'Success',
                    "status" => 1
                ],200);
            }else{
                return response()->json([
                    "message" => 'Sorry something went wrong...',
                    "status" => 0
                ],404);
            }
        }
    }

    // forgot password
    public function forgot_password(Request $request){

        $input = $request->all();
        $validator = Validator::make($input, [
            'email' => 'required|email|regex:/^[a-zA-Z]{1}/',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        $user = Userlist::where('email',$input['email'])->first();
        if(is_object($user)){
            $otp = rand(1000,9999);
            Userlist::where('id',$user->id)->update(['otp'=> $otp ]);
            $mail_header = array("otp" => $otp);
            $this->send_mail($mail_header,'Reset Password',$input['email']);
            return response()->json([
                "result" => Userlist::select('id', 'otp')->where('id',$user->id)->first(),
                "message" => 'Success',
                "status" => 1
            ]);
        }else{
            return response()->json([
                "message" => 'Invalid email address',
                "status" => 0
            ]);
        }

    }

    public function reset_password(Request $request){

        $user= Userlist::where('id', $request->id)->first();
        print($request->old_password);
        if (!$user || !Hash::check($request->old_password, $user->password)) {
            return response([
                'message' => ['These credentials do not match our records.']
            ], 404);
        }

        $input = $request->all();
        $validator = Validator::make($input, [
            'id' => 'required',
            'old_password' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        $options = [
            'cost' => 12,
        ];
        $request->password = password_hash($request->password, PASSWORD_DEFAULT, $options);

        if(Userlist::where('id',$request->id)->update($input)){
            return response()->json([
                "message" => 'Success',
                "status" => 200
            ]);
        }else{
            return response()->json([
                "message" => 'Invalid email address',
                "status" => 404
            ],404);
        }
    }

    public function getProfile($id){
        $user = UserList::find($id);
        if($user){
            return response()->json([
                "status" => 200,
                "data" => $user
            ],404);
        }else{
            return response()->json([
                "status" => 200,
                "message" => "User Doesn't exists"
            ],404);
        }
    }
}
