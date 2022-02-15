<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Userlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class CategorieController extends Controller
{
    // index
    // public function index()
    // {
    //     // $you = auth()->user();
    //     $users = Categorie::all();
    //     return view('dashboard.Categories.categories', compact('users'));
    // }

    public function create_categorie(Request $req){
        $rules = array(
            "categories" => "required",
            "user_id" => "required",
        );
        $validator = Validator::make($req->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }else{
            $user = Userlist::find($req->user_id);
            if ($user) {
            $categories_check = Categorie::where('user_id',$req->user_id)->first();
                if($categories_check){
                    $categories_check->categories=$req->categories;
                    $categories_check->save();
                }else{
                    $categories = new Categorie;
                    $categories->categories = $req->categories;
                    $categories->user_id = $req->user_id;
                    $categories->save();
                }
                return response()->json([
                    "status" => 200,
                    "message"=>"Success",
                ], 200);
            } else {
                return response()->json([
                "status" => 404,
                "message"=>"User not exists",
            ], 404);
            }
        }
    }

    public function categorie($user_id){
        $user = Userlist::find($user_id);
        if ($user) {
            if(Categorie::where(['user_id'=>$user_id])->first()){
                $list = Categorie::where(['user_id'=>$user_id])->first();
                // $data  = $list->map(function ($e){
                //     $element = $e['categories'];
                //     return $element;
                // });
                return response()->json([
                    "status" => 200,
                    "message"=>"Success",
                    "data"=>$list
                ], 200);
            }else{
                return response()->json([
                    "status" => 404,
                    "message"=>"User doesn't have categories",
                ], 404);
            }
        }else{
            return response()->json([
                "status" => 404,
                "message"=>"User not exists",
            ], 404);
        }
    }

    public function update_categories(Request $req){
        $rules = array(
            "categories" => "required",
            "user_id" => "required",
        );
        $validator = Validator::make($req->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }else{
            $categories = Categorie::where('user_id',$req->user_id)->first();
            if($categories){
                $categories->categories=$req->categories;
                $categories->save();
                return response()->json([
                    "status" => 200,
                    "message"=>"Success"
                ], 200);
            }else{
                return response()->json([
                    "status" => 404,
                    "message"=>"User doesn't have categories",
                ], 404);
            }
        }
    }
}
