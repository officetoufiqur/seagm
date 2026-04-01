<?php

namespace App\Helpers;

use App\Models\User;

class UserName
{
    public static function generate()
    {
        do {
            $date = now()->format('ymd'); 
            $random = mt_rand(100000, 999999);

            $username = 'U' . $date . $random;

        } while (User::where('username', $username)->exists());

        return $username;
    }
}
