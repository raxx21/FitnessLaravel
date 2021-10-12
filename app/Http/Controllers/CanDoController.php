<?php

namespace App\Http\Controllers;

use App\Models\Cando;
use Illuminate\Http\Request;

class CanDoController extends Controller
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
}
