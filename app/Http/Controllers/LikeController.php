<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use App\Models\Userlist;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function like($user_id,$post_id){
        $user = Userlist::find($user_id);
        if($user){
            $post = Post::find($post_id);
            if($post){
                if(Like::where([
                    ['user_id',$user_id],
                    ['post_id',$post_id]
                ])){
                    $delete = Like::where([
                        ['user_id',$user_id],
                        ['post_id',$post_id]
                    ])->first();
                    $delete->delete();
                    return response()->json([
                        "status" => 200,
                        "message"=>"Not liked",
                    ],200);
                }else{
                    $like = new Like;
                    $like->user_id = $user_id;
                    $like->post_id = $post_id;
                    $like->save();
                    return response()->json([
                        "status" => 200,
                        "message"=>"Liked",
                    ],200);
                }
            }else{
                return response()->json([
                    "status" => 404,
                    "message"=>"Post not exists",
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
