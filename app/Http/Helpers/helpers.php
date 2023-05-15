<?php

use App\Enums\Setting as EnumsSetting;
use Carbon\Carbon;
use App\Models\User;
use App\Models\UserImage;
use App\Models\UserNetwork;
use App\Models\Bonus;
use App\Models\BonusLog;
use App\Models\Prospect;
use App\Models\Serial;
use App\Models\Setting;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

if (!function_exists('generate_otp')) {
    function generate_otp()
    {
        return random_int(1234, 9899);
    }
}

if (!function_exists('getIpInfo')) {

    function getIpInfo()
    {
        $ip = $_SERVER["REMOTE_ADDR"];

        //Deep detect ip
        if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)){
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)){
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        $xml = @simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=" . $ip);

        $country = @$xml->geoplugin_countryName;
        $city = @$xml->geoplugin_city;
        $area = @$xml->geoplugin_areaCode;
        $code = @$xml->geoplugin_countryCode;
        $long = @$xml->geoplugin_longitude;
        $lat = @$xml->geoplugin_latitude;

        $data['country'] = $country;
        $data['city'] = $city;
        $data['area'] = $area;
        $data['code'] = $code;
        $data['long'] = $long;
        $data['lat'] = $lat;
        $data['ip'] = request()->ip();
        $data['time'] = date('d-m-Y h:i:s A');

        return $data;
    }
}

if (!function_exists('getOsBrowser')) {

    function getOsBrowser()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $osPlatform = "Unknown OS Platform";
        $osArray = array(
            '/windows nt 10/i' => 'Windows 10',
            '/windows nt 6.3/i' => 'Windows 8.1',
            '/windows nt 6.2/i' => 'Windows 8',
            '/windows nt 6.1/i' => 'Windows 7',
            '/windows nt 6.0/i' => 'Windows Vista',
            '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
            '/windows nt 5.1/i' => 'Windows XP',
            '/windows xp/i' => 'Windows XP',
            '/windows nt 5.0/i' => 'Windows 2000',
            '/windows me/i' => 'Windows ME',
            '/win98/i' => 'Windows 98',
            '/win95/i' => 'Windows 95',
            '/win16/i' => 'Windows 3.11',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i' => 'Mac OS 9',
            '/linux/i' => 'Linux',
            '/ubuntu/i' => 'Ubuntu',
            '/iphone/i' => 'iPhone',
            '/ipod/i' => 'iPod',
            '/ipad/i' => 'iPad',
            '/android/i' => 'Android',
            '/blackberry/i' => 'BlackBerry',
            '/webos/i' => 'Mobile'
        );
        foreach ($osArray as $regex => $value) {
            if (preg_match($regex, $userAgent)) {
                $osPlatform = $value;
            }
        }
        $browser = "Unknown Browser";
        $browserArray = array(
            '/msie/i' => 'Internet Explorer',
            '/firefox/i' => 'Firefox',
            '/safari/i' => 'Safari',
            '/chrome/i' => 'Chrome',
            '/edge/i' => 'Edge',
            '/opera/i' => 'Opera',
            '/netscape/i' => 'Netscape',
            '/maxthon/i' => 'Maxthon',
            '/konqueror/i' => 'Konqueror',
            '/mobile/i' => 'Handheld Browser'
        );
        foreach ($browserArray as $regex => $value) {
            if (preg_match($regex, $userAgent)) {
                $browser = $value;
            }
        }

        $data['os_platform'] = $osPlatform;
        $data['browser'] = $browser;

        return $data;
    }
}

if (!function_exists('sendEmail')) {

    function sendEmail($data)
    {
        $subjects = [
            'register' => 'Pendaftaran Berhasil',
            'prospect_register' => 'Pendaftaran Calon Member',
            'prospect_registered' => 'Proses Pendaftaran Telah Selesai',
            'otp' => 'Kode OTP Verifikasi',
            'new_prospect' => 'Prospect Baru',
            'paid_prospect_to_sponsor' => 'Aktivasi Prospek',
            'paid_prospect_to_prospect' => 'Aktivasi Akun',
            'custom_email' => $data['custom_subject'] ?? ""
        ];

        $setting = Setting::where('type', EnumsSetting::General)->get();

        $config = [
            'name' => $setting->where('key', 'email_sender_name')->first()->value,
            'email' => $setting->where('key', 'email_sender_email')->first()->value,
            'host' => $setting->where('key', 'email_host')->first()->value,
            'port' => $setting->where('key', 'email_port')->first()->value,
            'username' => $setting->where('key', 'email_username')->first()->value,
            'password' => $setting->where('key', 'email_password')->first()->value,
            'encryption' => $setting->where('key', 'email_encryption')->first()->value,
        ];

        $mail = new PHPMailer(true);
        $html = isset($data['message']) ? $data['message'] : view('email_template.'.$data['service'], $data['content'])->render();

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host       = $config['host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $config['username'];
            $mail->Password   = $config['password'];
            if ($config['encryption'] == 'SSL') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            }else{
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }
            $mail->Port       = $config['port'];
            $mail->CharSet = 'UTF-8';
            //Recipients
            $mail->setFrom($config['email'], $config['name']);
            $mail->addAddress($data['receiver'], $data['receiver_name']);
            $mail->addReplyTo($config['email'], $config['name']);
            // Content
            $mail->isHTML(true);
            $mail->Subject = $data['subject'];
            $mail->Body    = $html;
            $mail->send();

            $mail;
        } catch ( \Throwable $e) {
            return $e->getMessage();
        }

        return true;
    }
}

if (!function_exists('formatWhatsappNumber')) {
    function formatWhatsappNumber($number) {
        if ($number != NULL || $number != "") {
            $split = str_split($number);
            if ($split[0] == 0) {
                $split[0] = '62';
                $number = implode('', $split);
            }
        }
        return $number;
    }
}
