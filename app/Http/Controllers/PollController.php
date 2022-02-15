<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupMember;
use App\Models\Poll;
use App\Models\PollAnswer;
use App\Models\Userlist;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class PollController extends Controller
{
    public function createPoll(Request $request){
        $rules = array(
            "poll_question" => "required",
            "user_id" => "required",
            "firebase_id" => "required",
            "group_id" => "required",
            "option1"=>"required",
            "option2"=>"required"
        );
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }else{
            if (Userlist::where('id', $request->user_id)->first()) {
                if (Group::where('id', $request->group_id)->first()) {
                    if(GroupMember::where([
                        ['group_id',$request->group_id],
                        ['user_id',$request->user_id]
                    ])->first()){
                        $max_count = Group::find($request->group_id);
                        // print($max_count->active_members);
                        $value = new Poll;
                        $value->poll_question=$request->poll_question;
                        $value->user_id=$request->user_id;
                        $value->firebase_id=$request->firebase_id;
                        $value->option1=$request->option1;
                        $value->option2=$request->option2;
                        $value->option3=$request->option3;
                        $value->option4=$request->option4;
                        $value->expire=0;
                        $value->max_member=$max_count->active_members;
                        $value->group_id=$request->group_id;
                        $result = $value->save();
                        if ($result) {
                            return  response()->json([
                            "status" => 200,
                            "message"=>"success",
                        ], 200);
                        } else {
                            return response()->json([
                            "status" => 404,
                            "message"=>"Something went wrong",
                        ], 404);
                        }
                    }else {
                        return response()->json([
                                "status" => 404,
                                "message"=>"User doesn't exists in this group",
                            ], 404);
                    }

                }else {
                    return response()->json([
                            "status" => 404,
                            "message"=>"group not exists",
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

    public function pollAnswer(Request $request){
        $rules = array(
            "poll_id" => "required",
            "user_id" => "required",
            "answer" => "required",
            "firebase_id"=>"required"
        );
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }else{
            $user = UserList::find($request->user_id);
            if ($user) {
                $poll = Poll::find($request->poll_id);
                if ($poll) {
                    if(PollAnswer::where([
                        ['poll_id',$request->poll_id],
                        ['user_id',$request->user_id],
                    ])->first()){
                        return response()->json([
                            "status" => 404,
                            "message"=>"User Already answered this poll",
                        ], 404);
                    }else{
                        if($poll->expire == 0){
                            if (GroupMember::where([
                            ['group_id',$poll->group_id],
                            ['user_id',$request->user_id]
                        ])->first()) {
                                if (PollAnswer::where([
                                ['poll_id',$request->poll_id],
                                ['user_id',$request->user_id],
                                ['answer',$request->answer]
                            ])->first()) {
                                    return response()->json([
                                    "status" => 404,
                                    "message"=>"You already answer to it",
                                ], 404);
                                } else {
                                    $value = new PollAnswer;
                                    $value->poll_id=$request->poll_id;
                                    $value->user_id=$request->user_id;
                                    $value->firebase_id=$request->firebase_id;
                                    $value->answer=$request->answer;
                                    $result = $value->save();
                                    if ($result) {
                                        $hasAnswered = $request->answer;
                                        $option_1 =  PollAnswer::where([
                                            ['answer','1'],
                                            ['firebase_id',$request->firebase_id]
                                        ])->get();
                                        $option_2 =  PollAnswer::where([
                                            ['answer','2'],
                                            ['firebase_id',$request->firebase_id]

                                        ])->get();
                                        $option_3 =  PollAnswer::where([
                                            ['answer','3'],
                                            ['firebase_id',$request->firebase_id]

                                        ])->get();
                                        $option_4 =  PollAnswer::where([
                                            ['answer','4'],
                                            ['firebase_id',$request->firebase_id]

                                        ])->get();
                                        $option1  = $option_1->map(function ($e){
                                            $element = UserList::find($e['user_id']);
                                            return $element;
                                        });
                                        $option2  = $option_2->map(function ($e){
                                            $element = UserList::find($e['user_id']);
                                            return $element;
                                        });
                                        $option3  = $option_3->map(function ($e){
                                            $element = UserList::find($e['user_id']);
                                            return $element;
                                        });
                                        $option4  = $option_4->map(function ($e){
                                            $element = UserList::find($e['user_id']);
                                            return $element;
                                        });
                                        return response()->json([
                                            "status" => 200,
                                            "data" => ["poll"=>$poll,"hasAnswered" => $hasAnswered,"option1"=>$option1,"option2"=>$option2,"option3"=>$option3,"option4"=>$option4],
                                            "message"=>"success",
                                        ], 200);
                                    } else {
                                        return response()->json([
                                        "status" => 404,
                                        "message"=>"Something went wrong",
                                    ], 404);
                                    }
                                }
                            } else {
                                return response()->json([
                                    "status" => 404,
                                    "message"=>"User is not in this group",
                                ], 404);
                            }
                        }else{
                            return response()->json([
                                "status" => 404,
                                "message"=>"Poll is Full",
                            ], 404);
                        }
                    }
                } else {
                    return response()->json([
                    "status" => 404,
                    "message"=>"Poll not exists",
                ], 404);
                }
            } else {
                return response()->json([
                "status" => 404,
                "message"=>"User not exists",
            ], 404);
            }
        }
    }

    public function pollAnswerByUser($pollId,$userId){
        $user = UserList::find($userId);
        if($user){
            $poll = Poll::find($pollId);
            if($poll){
                $answer = PollAnswer::where([
                    ['user_id',$userId],
                    ['poll_id',$pollId]
                ])->first();
                if($answer){
                    $result = $answer->answer;
                    return response()->json([
                        "status" => 200,
                        "data" => $result,
                        "message"=>"success",
                    ], 200);
                }else{
                    return response()->json([
                        "status" => 404,
                        "message"=>"Something went wrong",
                    ], 404);
                }
            }else{
                return response()->json([
                    "status" => 404,
                    "message"=>"Poll not exists",
                ], 404);
            }
        }else{
            return response()->json([
                "status" => 404,
                "message"=>"User not exists",
            ], 404);
        }
    }

    public function Poll($firebaseid,$userId){
        $user = UserList::find($userId);
        if($user){
            $poll = Poll::where(['firebase_id'=>$firebaseid])->first();
            if($poll){
                if(PollAnswer::where([
                    ['firebase_id',$firebaseid],
                    ['user_id',$userId]
                ])->first()){
                    $answer = PollAnswer::where([
                        ['firebase_id',$firebaseid],
                        ['user_id',$userId]
                    ])->first();
                    $hasAnswered = $answer->answer;
                }else{
                    $hasAnswered = 0;
                }
                $option_1 =  PollAnswer::where([
                    ['answer','1'],
                    ['firebase_id',$firebaseid]
                ])->get();
                $option_2 =  PollAnswer::where([
                    ['answer','2'],
                    ['firebase_id',$firebaseid]

                ])->get();
                $option_3 =  PollAnswer::where([
                    ['answer','3'],
                    ['firebase_id',$firebaseid]

                ])->get();
                $option_4 =  PollAnswer::where([
                    ['answer','4'],
                    ['firebase_id',$firebaseid]

                ])->get();
                $option1  = $option_1->map(function ($e){
                    $element = UserList::find($e['user_id']);
                    return $element;
                });
                $option2  = $option_2->map(function ($e){
                    $element = UserList::find($e['user_id']);
                    return $element;
                });
                $option3  = $option_3->map(function ($e){
                    $element = UserList::find($e['user_id']);
                    return $element;
                });
                $option4  = $option_4->map(function ($e){
                    $element = UserList::find($e['user_id']);
                    return $element;
                });
                return response()->json([
                    "status" => 200,
                    "data" => ["poll"=>$poll,"hasAnswered" => $hasAnswered,"option1"=>$option1,"option2"=>$option2,"option3"=>$option3,"option4"=>$option4],
                    "message"=>"success",
                ], 200);
            } else {
                    return response()->json([
                    "status" => 404,
                    "message"=>"Poll not exists",
                ], 404);
            }
        }else{
            return response()->json([
                "status" => 404,
                "message"=>"User not exists",
            ], 404);
        }
    }

    public function pollYes($pollId){
        $poll = Poll::find($pollId);
        if($poll){
            $user= PollAnswer::where([
                ['poll_id',$pollId],
                ['answer',1],
                ])->get();
            $data  = $user->map(function ($e){
                $element = UserList::find($e['user_id']);
                return $element;
            });
            return response()->json([
                "status" => 200,
                "data" => $data,
                "message"=>"success",
            ], 200);
        } else {
                return response()->json([
                "status" => 404,
                "message"=>"Poll not exists",
            ], 404);
        }
    }

    public function pollNo($pollId){
        $poll = Poll::find($pollId);
        if($poll){
            $user= PollAnswer::where([
                ['poll_id',$pollId],
                ['answer',0],
                ])->get();
            $data  = $user->map(function ($e){
                $element = UserList::find($e['user_id']);
                return $element;
            });
            return response()->json([
                "status" => 200,
                "data" => $data,
                "message"=>"success",
            ], 200);
        } else {
                return response()->json([
                "status" => 404,
                "message"=>"Poll not exists",
            ], 404);
        }
    }
}
