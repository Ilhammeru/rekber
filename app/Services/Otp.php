<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;

class Otp {
    public function validate($via = 'email', $otp, $email)
    {
        $real = decrypt($email);
        $select = 'email_otp';
        $update_key = 'email_verified_at';
        if ($via == 'phone') {
            $select = 'phone_otp';
            $update_key = 'phone_verified_at';
        }

        $data = User::where('email', $real)
            ->first();

        $res = false;
        if ($otp == $data->$select) {
            $res = true;

            $data->$update_key = Carbon::now();
            $data->save();
        }

        return $res;
    }
}
