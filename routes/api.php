<?php

use App\Http\Controllers\ActivitiesController;
use App\Http\Controllers\CanDoController;
use App\Http\Controllers\CannotDoController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\GroupMemberController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PollController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PrefrenceController;
use App\Models\Comment;
use App\Models\Prefrence;
use Database\Seeders\UserList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PHPUnit\TextUI\XmlConfiguration\GroupCollection;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


// User Api
Route::get('users','UserLists@getUserListApi');
Route::post('register','UserLists@register');
Route::post('updateUser/{id}','UserLists@update_user');
Route::delete('deleteUser/{id}','UserLists@delete_user');
Route::post('profile_picture', 'UserLists@profile_picture');
Route::get('get_profile/{id}', 'UserLists@getProfile');
Route::post('reset_password', [ForgotPasswordController::class,'reset']);
Route::post('forgot_password', [ForgotPasswordController::class,'forgot']);
Route::post('createCando/{id}', [CanDoController::class,'createCando']);
Route::get('cando/{userid}', [CanDoController::class,'Cando']);
Route::delete('deleteCando/{id}', [CanDoController::class,'deleteCando']);
Route::post('createCannotdo/{id}', [CannotDoController::class,'createCannotdo']);
Route::get('cannotdo/{userid}', [CannotDoController::class,'Cannotdo']);
Route::delete('deleteCannotdo/{id}', [CannotDoController::class,'deleteCannotdo']);
Route::post('createGoal/{id}', [GoalController::class,'createGoal']);
Route::get('goal/{userid}', [GoalController::class,'goal']);
Route::delete('deleteGoal/{id}', [GoalController::class,'deleteGoal']);
// Group apis
Route::post('createGroup/{id}', [GroupsController::class,'createGroup']);
Route::get('group_members/{id}', [GroupMemberController::class,'getGroupMember']);
Route::get('get_my_group/{id}', [GroupsController::class,'getMyOwnGroup']);
Route::get('join_group/{id}/{groupId}', [GroupsController::class,'joinGroup']);
Route::get('get_group/{id}', [GroupsController::class,'getGroupNotAdmin']);
Route::get('get_all_group', [GroupsController::class,'getAllGroup']);
Route::get('group/{group_id}', [GroupsController::class,'group']);
//Group Invitaion
Route::get('group_invitations/{group_id}', [GroupsController::class,'group_invitations']);
Route::post('invitation_response', [GroupsController::class,'invitation_response']);
// User Invitation
Route::get('invite_user_list/{group_id}', [GroupsController::class,'invite_user_list']);
// Activity apis
Route::post('create_activity', [ActivitiesController::class,'createActivity']);
Route::get('activity/{id}', [ActivitiesController::class,'activity']);
Route::delete('delete_activity/{id}', [ActivitiesController::class,'deleteActivity']);
// Event apis
Route::post('create_event', [EventController::class,'createEvent']);
Route::get('event/{id}', [EventController::class,'event']);
Route::get('checkin/{eventId}/{userId}', [EventController::class,'checkIN']);
Route::get('eventCheckIN/{eventId}', [EventController::class,'eventCheckIN']);
Route::get('checkOUT/{eventId}/{userId}', [EventController::class,'checkOUT']);
Route::get('eventDisplay/{eventId}', [EventController::class,'eventDisplay']);
Route::delete('eventDelete/{eventId}', [EventController::class,'eventDelete']);
// Prefrences
Route::post('create_prefrence', [PrefrenceController::class,'createPrefrence']);
Route::get('prefrence/{user_id}', [PrefrenceController::class,'prefrence']);
Route::delete('deletePrefrence/{id}', [PrefrenceController::class,'deletePrefrence']);
// Poll
Route::post('createPoll', [PollController::class,'createPoll']);
Route::post('pollAnswer', [PollController::class,'pollAnswer']);
Route::get('Poll/{id}/{userId}', [PollController::class,'Poll']);
Route::get('pollAnswerByUser/{pollId}/{userId}', [PollController::class,'pollAnswerByUser']);
Route::get('pollYes/{pollId}', [PollController::class,'pollYes']);
Route::get('pollNo/{pollId}', [PollController::class,'pollNo']);
//Posts
Route::post('create_post', [PostController::class,'create_post']);
Route::get('post_by_user/{user_id}', [PostController::class,'post_by_user']);
//Like
Route::get('like/{user_id}/{post_id}', [LikeController::class,'like']);
//Comments
Route::post('create_comment', [CommentController::class,'create_comment']);
//Categories
Route::post('create_categorie', [CategorieController::class,'create_categorie']);
Route::get('categorie/{user_id}', [CategorieController::class,'categorie']);
Route::post('update_categories', [CategorieController::class,'update_categories']);





Route::middleware('auth:api')->get('/user-get', function (Request $request) {
    return $request;
});

Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::get('get',[UserList::class,'get']);
});

Route::post('login','UserLists@login');



