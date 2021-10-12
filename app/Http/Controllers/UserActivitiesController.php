<?php

namespace App\Http\Controllers;

use App\Models\UserActivitie;
use Illuminate\Http\Request;

class UserActivitiesController extends Controller
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
        $userlist = UserActivitie::all();
        return view('dashboard.users.useractivities.activitiesShow', compact('userlist'));
    }
}
