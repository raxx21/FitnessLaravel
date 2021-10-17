<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::group(['middleware' => 'auth:sanctum'], function(){
// User Api
Route::get('users','UserLists@getUserListApi');
Route::post('register','UserLists@register');
Route::post('updateUser/{id}','UserLists@update_user');
Route::delete('deleteUser/{id}','UserLists@delete_user');
Route::post('profile_picture', 'UserLists@profile_picture');
Route::post('reset_password', 'UserLists@reset_password');

Route::post('forgot_password', [Userlist::class,'forgot_password']);


// });

Route::post('login','UserLists@login');



