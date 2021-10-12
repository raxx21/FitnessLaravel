<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Userlist;
// use Illuminate\Support\Facades\Validator;

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

    public function registerapi(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'user_name' => 'required',
            'email' => 'required|email|regex:/^[a-zA-Z]{1}/|unique:users,email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        $options = [
            'cost' => 12,
        ];
        $input['password'] = password_hash($input["password"], PASSWORD_DEFAULT, $options);
        $input['status'] = 1;

        $user = UserList::create($input);

        if (is_object($user)) {
            return response()->json([
                "result" => $user,
                "message" => 'Registered Successfully',
                "status" => 1
            ]);
        } else {
            return response()->json([
                "message" => 'Sorry, something went wrong !',
                "status" => 0
            ]);
        }

    }

}
