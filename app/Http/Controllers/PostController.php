<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\Userlist;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function create_post(Request $req){
        $rules = array(
            "user_id" => "required",
            "location" => "required",
            "image" => "required|image|mimes:jpeg,png,jpg,gif,svg",
        );
        $validator = Validator::make($req->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),401);
        }else{
            $user = UserList::find($req->user_id);
            if ($user) {
                if ($req->hasFile('image')) {
                    $image = $req->file('image');
                    $name = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('uploads/posts');
                    $image->move($destinationPath, $name);
                    if ($image) {
                        $post = new Post;
                        $post->user_id = $req->user_id;
                        $post->location = $req->location;
                        $post->image = '/posts/'.$name;
                        $post->likes = 0;
                        $post->comments = 0;
                        $post->caption = $req->caption;
                        $post->save();
                        return response()->json([
                            "status" => 200,
                            "message" => 'Success',
                        ], 200);
                    } else {
                        return response()->json([
                            "message" => 'Sorry something went wrong...',
                            "status" => 0
                        ], 404);
                    }
                } else {
                    return response()->json([
                        "message" => 'Sorry something went wrong...',
                        "status" => 0
                    ], 404);
                }
            }else{
                return response()->json([
                    "status" => 404,
                    "message"=>"User not exists",
                ],404);
            }
        }
    }

    public function post_by_user($user_id){
        $user = UserList::find($user_id);
        if($user){
            if(Post::where('user_id',$user_id)->first()){
                $post = Post::where('user_id',$user_id)->get();
                return response()->json([
                    "status" => 200,
                    "message"=>"success",
                    "data"=>$post
                ],200);
            }else{
                return response()->json([
                    "status" => 404,
                    "message"=>"Empty here.",
                ],404);
            }
        }else{
            return response()->json([
                "status" => 404,
                "message"=>"User not exists",
            ],404);
        }
    }
}
