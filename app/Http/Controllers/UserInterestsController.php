<?php

namespace App\Http\Controllers;

use App\Models\UserInterest;
use Illuminate\Http\Request;

class UserInterestsController extends Controller
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
        $userlist = UserInterest::all();
        return view('dashboard.users.userinterest.userinterests', compact('userlist'));
    }
}
