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
use App\Services\Otp;
use App\Services\RegisterService;
use App\Services\Whatsapp;
use App\Services\Zenziva;
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

            return $this->success(
                __('global.otp_send'),
                [
                    'url' => route(
                        url('register/otp/?via=email&g=' . encrypt($user->email))
                    )
                ]
            );
        } catch (\Throwable $th) {

            return $this->error($this->messageError($th));
        }
    }

    /**
     * Function to show otp form
     *
     * @return JsonResponse
     */
    public function otpForm()
    {
        $email = request()->get('g');
        $via = request()->get('via');

        $check_key = 'email_verified_at';
        if ($via == 'whatsapp') {
            $check_key = 'phone_verified_at';
        }

        // check email verification
        $real = decrypt($email);
        $data = User::select($check_key, 'phone')
            ->where('email', $real)
            ->first();
        $phone = $data->phone;

        if ($data->$check_key) {
            return view('auth.otp_verified',compact('via'));
        }
        if ($via == 'email') {
            return view('auth.otp', compact('email'));
        } else {
            return view('auth.otp_whatsapp', compact('email', 'phone'));
        }
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

    /**
     * Function to validate OTP via whatsapp
     * @param \Illuminate\Http\Request
     * @param \App\Services
     *
     * @return JsonResponse
     */
    public function submitOtpWhatsapp(Request $request, Otp $service)
    {
        $email = $request->email;
        $otp = $request->otp;

        if (!$otp) {
            return $this->buildValidationError(['otp' => [__("global.otp_required")]]);
        }

        $verify = $service->validate('phone', $otp, $email);
        if (!$verify) {
            return $this->buildValidationError(['otp' => [__("global.otp_mismatch")]]);
        }

        return $this->success(__('global.phone_verified') . '. ' . __('global.redirecting_login'), ['url' => route('login')]);
    }

    /**
     * Function to send otp via whatsapp
     * @return JsonResponse
     */
    public function sendOtp(Request $request, Zenziva $service)
    {
        $phone = $request->phone;
        $otp = generate_otp();

        $send = $service->send_message('whatsapp', $phone, 'Kode verifikasi anda ' . $otp);

        // save otp if success
        if ($send['status'] == 200) {
            User::where('phone', $phone)
                ->update([
                    'phone_otp' => $otp,
                ]);
        }

        return $this->success(__('otp_send_wa'));
    }
}
