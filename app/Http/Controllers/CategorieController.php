<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    // index
    public function index()
    {
        // $you = auth()->user();
        $users = Categorie::all();
        return view('dashboard.Categories.categories', compact('users'));
    }
}
