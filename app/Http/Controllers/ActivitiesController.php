<?php

namespace App\Http\Controllers;

use App\Models\Activitie;
use Illuminate\Http\Request;

class ActivitiesController extends Controller
{
    // index
    public function index()
    {
        // $you = auth()->user();
        $users = Activitie::all();
        return view('dashboard.activities.activities', compact('users'));
    }
}
