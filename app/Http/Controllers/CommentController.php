<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\Userlist;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function create_comment(Request $req){
        $rules = array(
            "comment_user_id" => "required",
            "comment" => "required",
            "post_id" => "required",
        );
        $validator = Validator::make($req->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),401);
        }else{
            $user = Userlist::find($req->comment_user_id);
            if($user){
                $post = Post::find($req->post_id);
                if($post){
                    $comment = new Comment;
                    $comment->comment_user_id = $req->comment_user_id;
                    $comment->comment = $req->comment;
                    $comment->post_id = $req->post_id;
                    $comment->save();
                    return response()->json([
                        "status" => 200,
                        "message"=>"Success"
                    ],200);
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
}
