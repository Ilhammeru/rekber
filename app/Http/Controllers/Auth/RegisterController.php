<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\EmailOtpJob;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Setting;
use App\Models\Prospect;
use App\Models\District;
use App\Models\Serial;
use App\Models\SerialLog;
use App\Models\ServiceCode;
use App\Models\UserNetwork;
use App\Services\RegisterService;
use App\Services\Whatsapp;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', [
            'except' => ['showRegistrationForm', 'submitRegister']
        ]);
    }

    public function index()
    {
        return view('auth.register');
    }

    /**
     * Function to store user data and send otp data to email
     *
     * @return JsonResponse
     */
    public function submit(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username',
            'password' => 'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[-+_!@#$%^&*.,?]).+$/',
            'email' => 'required|email:dns,rfc|unique:users,email',
            'phone' => 'required',
        ]);

        try {
            // store
            $otp = generate_otp();
            $payload = $request->toArray();
            $payload['email_otp'] = $otp;
            $payload['status'] = User::INACTIVE;
            $payload['password'] = Hash::make($payload['password']);
            $user = User::create($payload);

            EmailOtpJob::dispatch($user);

            return $this->success(__('global.otp_send'), ['url' => route('register.otp', encrypt($user->email))]);
        } catch (\Throwable $th) {

            return $this->error($this->messageError($th));
        }
    }

    /**
     * Function to show otp form
     *
     * @return JsonResponse
     */
    public function otpForm($email)
    {
        // check email verification
        $real = decrypt($email);
        $data = User::select('email_verified_at')
            ->where('email', $real)
            ->first();

        if ($data->email_verified_at) {
            return view('auth.otp_verified');
        }
        return view('auth.otp', compact('email'));
    }

    public function submitOtp(Request $request, $email)
    {
        $email = decrypt($email);
        $otp = $request->otp;

        $data = User::where('email', $email)
            ->first();

        if ($data->email_otp != $otp) {
            return $this->buildValidationError(['otp' => [__('global.otp_mismatch')]]);
        }

        $data->status = User::ACTIVE;
        $data->email_verified_at = Carbon::now();
        $data->save();

        return $this->success(__('global.email_verified') . '. ' . __('global.redirecting_login'), ['url' => route('login')]);
    }
}
