<?php

namespace App\Http\Controllers;

use App\Models\Interest;
use Illuminate\Http\Request;

class InterestController extends Controller
{
     // index
     public function index()
     {
         $users = Interest::all();
         return view('dashboard.Interest.interest', compact('users'));
     }
}
