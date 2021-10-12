<?php

namespace App\Http\Controllers;

use App\Models\Cannotdo;
use Illuminate\Http\Request;

class CannotDoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

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
}
