<?php

use App\Http\Controllers\ActivitiesController;
use App\Http\Controllers\CanDoController;
use App\Http\Controllers\CannotDoController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\GroupMemberController;
use App\Http\Controllers\GroupsController;
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
// Activity apis
Route::post('create_activity', [ActivitiesController::class,'createActivity']);
Route::get('activity/{id}', [ActivitiesController::class,'activity']);
Route::delete('delete_activity/{id}', [ActivitiesController::class,'deleteActivity']);
// Event apis
Route::post('create_event', [EventController::class,'createEvent']);
Route::get('event/{id}', [EventController::class,'event']);


Route::middleware('auth:api')->get('/user-get', function (Request $request) {
    return $request;
});

Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::get('get',[UserList::class,'get']);
});

Route::post('login','UserLists@login');



