<?php

namespace App\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Notifications\ResetPasswordNotification;

class Userlist extends Model implements CanResetPasswordContract
{
    use Notifiable;
    use CanResetPassword;
    use HasApiTokens;
    use HasFactory;

    protected $fillable = [
        'id'
    ];

    public function sendPasswordResetNotification($token)
    {
        $url = 'https://spa.test/reset-password?token='.$token;

        $this->notify(new ResetPasswordNotification($url));
    }
}
