<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activitie;
use App\Models\Event;
use App\Models\EventMember;
use App\Models\Group;
use App\Models\Userlist;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    // Api Function
    public function createEvent(Request $request){
        $rules = array(
            "title" => "required",
            "description" => "required",
            "location" => "required",
            "members" => "required",
            "date" => "required",
            "time" => "required",
            "user_id" => "required",
            "group_id" => "required",
        );
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }else{
            if(Userlist::where('id',$request->user_id)->first()){
                if(Group::where('id',$request->group_id)->first()){
                    $value = new Event;
                    $value->title=$request->title;
                    $value->description=$request->description;
                    $value->location=$request->location;
                    $value->members=$request->members;
                    $value->date=$request->date;
                    $value->time=$request->time;
                    $value->active=1;
                    $value->user_id=$request->user_id;
                    $value->group_id=$request->group_id;
                    $result = $value->save();
                    if($result){
                    $event_member= new EventMember;
                    $event_member->event_id=$value->id;
                    $event_member->user_id=$value->user_id;
                    $event_member->admin=1;
                    $event_member_result = $event_member->save();
                    if($event_member_result){
                        return  response()->json([
                            "status" => 200,
                            "message"=>"success",
                        ],200);
                    }
                    else{
                        return response()->json([
                            "status" => 400,
                            "message"=>"Something went wrong",
                        ],400);
                    }
                    }
                    else{
                        return response()->json([
                            "status" => 400,
                            "message"=>"Something went wrong",
                        ],400);
                    }
                }else{
                    return response()->json([
                        "status" => 400,
                        "message"=>"Group not exists",
                    ],400);
                }
            }else{
                return response()->json([
                    "status" => 400,
                    "message"=>"User not exists",
                ],400);
            }

        }
    }

    public function event($id){
        $event = Event::where('group_id',$id)->simplePaginate();
        if(Event::where('group_id',$id)->first()){
            return response()->json([
                "status" => 200,
                "data" => $event
            ],200);
        }
        else{
            return response()->json([
                "status" => 400,
                "message" => "Event doesn't exists"
            ],400);
        }
    }

    public function checkIN($eventId,$userId){
        $user = UserList::find($userId);
        if($user){
            $event = Event::find($eventId);
            if($event){
                $active_members = $event->active;
                $max_group_members = $event->members;
                if ($active_members < $max_group_members) {
                    if(EventMember::where('event_id',$eventId)->where('user_id',$userId)->first()){
                        return response()-> json([
                            "status" => 404,
                            "message" => "User Already joined the event"
                        ],404);
                    }else{
                    $insert_group_member = new EventMember;
                    $insert_group_member->event_id = $eventId;
                    $insert_group_member->user_id = $userId;
                    $result = $insert_group_member->save();
                    if($result){
                        $increase_members = $event->active + 1;
                        $event->active = $increase_members;
                        $result_active_member = $event->save();
                        if($result_active_member){
                            $event_member = EventMember::where('event_id',$eventId)->get();
                            if($event_member){
                                // $user = UserList::find($userId);
                                // $event_count = EventMember::where('user_id',$userId)->count();
                                // $user->group_count = $group_count;
                                // print($group_count);
                                // $user->save();
                                $data  = $event_member->map(function ($e){
                                    $element['user_id'] = UserList::find($e['user_id']);
                                    return $element;
                                });
                                return response()-> json([
                                    "status" => 200,
                                    // "data" => $data,
                                    "message" => "Successfully Joined the Event"
                                ],200);
                            }
                            else{
                                return response()-> json([
                                    "status" => 404,
                                    "message" => "Event Doesn't exists"
                                ],404);
                            }
                        }
                        else{
                            return response()-> json([
                                "status" => 404,
                                "message" => "Something went wrong"
                            ],404);
                        }
                    }else{
                            return response()-> json([
                                "status" => 404,
                                "message" => "Something went wrong"
                            ],404);
                        }
                    }
                }else{
                    return response()-> json([
                        "status" => 404,
                        "message" => "Event Is full"
                    ],404);
                }
            }else{
            return response()-> json([
                "status" => 404,
                "message" => "Event Doesn't exists"
            ],404);
        }

        }else{
            return response()-> json([
                "status" => 404,
                "message" => "User Doesn't exists"
            ],404);
        }
    }

    public function eventCheckIN($eventId){
        $event = Event::find($eventId);
        if($event){
            $event_member= EventMember::where('event_id',$eventId)->get();
            $data  = $event_member->map(function ($e){
                $element['user'] = UserList::find($e['user_id']);
                return $element;
            });
            return response()->json([
                'status' => 200,
                'data' => $data,
                "message"=>'success'
            ],200);
        }else{
            return response()-> json([
                "status" => 404,
                "message" => "Event Doesn't exists"
            ],404);
        }
    }

    public function checkOUT($eventId,$userId){
        $user = UserList::find($userId);
        if($user){
            $event = Event::find($eventId);
            if($event){
               $event_member = EventMember::where('event_id',$eventId)->where('user_id',$userId)->first();
               if($event_member){
                  $event_member->delete();
                  return response()-> json([
                    "status" => 200,
                    "message" => "Success"
                ],200);
               }else{
                return response()-> json([
                    "status" => 404,
                    "message" => "Something went wrong"
                ],404);
               }
            }else{
                return response()-> json([
                    "status" => 404,
                    "message" => "Event Doesn't exists"
                ],404);
            }
        }else{
            return response()-> json([
                "status" => 404,
                "message" => "User Doesn't exists"
            ],404);
        }
    }
}
