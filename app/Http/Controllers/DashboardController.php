<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Browser;

class DashboardController extends Controller
{
    public function index()
    {
        if (session()->get('session_save_history')) {
            // save log history
            $info = json_decode(json_encode(getIpInfo()), true);
            if (env('APP_ENV') != 'local') {
                $longitude =  @implode(',', $info['long']);
                $latitude =  @implode(',', $info['lat']);
                $city =  @implode(',', $info['city']);
                $country_code = @implode(',', $info['code']);
                $country =  @implode(',', $info['country']);
            } else {
                $country = 'Indonesia';
                $city = 'Malang';
            }

            $now = Carbon::now()->timezone(env('TIMEZONE'));
            \App\Models\UserLoginHistory::firstOrCreate([
                'user_id' => auth()->id(),
                'login_at' => $now,
                'ip_address' => getRealIP(),
                'location' => $country . ', ' . $city,
                'device' => Browser::browserName(),
            ]);

            session()->forget('session_save_history');
        }
        $pageTitle = "Dashboard";
        return view('dashboard', compact('pageTitle'));
    }
}
