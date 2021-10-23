<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\Userlist;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    function forgot(){
        $credentials = request()->validate(['email'=> 'required|email']);

        $status = Password::sendResetLink($credentials);
        print($status);

         if ($status === Password::RESET_LINK_SENT) {
            return[
                'status' => __($status),
            ];
         }
        // response()->json([
        //     "status" => 400,
        //     "message" => 'Something gone wrong'
        // ])
        // ;
    }

    public function reset(Request $request){
        $credentials = request()->validate([
            'email' => 'required|email',
            'password' => 'required',
            'token' => 'required'
        ]);

        $email_password_status = Password::reset($credentials, function($request){
            $user = Userlist::where('email', $request->email)->first();
            $options = [
                'rounds' => 12,
            ];
            $user->password = Hash::make($request->password, $options);
            $user->save();
        });

        if($email_password_status == Password::INVALID_TOKEN){
            return response()-> json([
                'status' => 400,
                'message' => 'Invalid Token'
            ]);
        }

        return response()-> json([
            'status' => 200,
            'message' => 'Password successfully changed'
        ]);
    }
}
