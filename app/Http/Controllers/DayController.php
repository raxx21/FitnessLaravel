<?php

namespace App\Http\Controllers;

use App\Models\Day;
use Illuminate\Http\Request;

class DayController extends Controller
{
    // index
    public function index()
    {
        // $you = auth()->user();
        $users = Day::all();
        return view('dashboard.Days.days', compact('users'));
    }
}
