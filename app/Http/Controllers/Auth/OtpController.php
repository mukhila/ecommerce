<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\SmsService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OtpController extends Controller
{
    protected function generateOtp(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    protected function dispatchOtp(string $phone, string $type): string
    {
        // Invalidate any pending OTPs for this phone+type
        Otp::where('phone', $phone)
            ->where('type', $type)
            ->whereNull('used_at')
            ->update(['used_at' => now()]);

        $otp = $this->generateOtp();

        Otp::create([
            'phone'      => $phone,
            'otp'        => $otp,
            'type'       => $type,
            'expires_at' => now()->addMinutes(10),
        ]);

        SmsService::send($phone, "Your JangaKids OTP is {$otp}. Valid for 10 minutes. Do not share this code with anyone.");
        Log::info("[OTP] Phone: {$phone} | Type: {$type} | OTP dispatched");

        return $otp;
    }

    // ─────────────────────────────────────────────
    //  LOGIN
    // ─────────────────────────────────────────────

    protected function verifyRecaptcha(string $token): bool
    {
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => config('services.recaptcha.secret_key'),
            'response' => $token,
            'remoteip' => request()->ip(),
        ]);

        $data = $response->json();

        if (!($data['success'] ?? false)) {
            return false;
        }

        // reCAPTCHA v3 returns a score (0.0–1.0); require at least 0.5
        if (isset($data['score'])) {
            return $data['score'] >= 0.5;
        }

        return true;
    }

    public function sendLoginOtp(Request $request)
    {
        $request->validate([
            'phone'              => ['required', 'string', 'regex:/^[6-9]\d{9}$/'],
            'g-recaptcha-response' => ['required', 'string'],
        ], [
            'phone.regex'                  => 'Please enter a valid 10-digit mobile number.',
            'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification.',
        ]);

        if (! $this->verifyRecaptcha($request->input('g-recaptcha-response'))) {
            return back()
                ->withErrors(['recaptcha' => 'reCAPTCHA verification failed. Please try again.'])
                ->withInput();
        }

        $phone = $request->phone;

        $user = User::where('phone', $phone)->first();
        if (!$user) {
            return back()
                ->withErrors(['phone' => 'No account found with this phone number. Please register first.'])
                ->withInput();
        }

        $otp = $this->dispatchOtp($phone, 'login');

        $request->session()->put('otp_phone', $phone);
        $request->session()->put('otp_type', 'login');

        if (config('app.env') === 'local') {
            return redirect()->route('login.verify')
                ->with('dev_otp', $otp)
                ->with('info', "Development Mode — Your OTP is: <strong>{$otp}</strong>");
        }

        return redirect()->route('login.verify')
            ->with('success', 'OTP sent to your registered mobile number. Valid for 10 minutes.');
    }

    public function showLoginVerify(Request $request)
    {
        if (!$request->session()->has('otp_phone')) {
            return redirect()->route('login');
        }

        return view('auth.otp-verify', [
            'type'  => 'login',
            'phone' => $request->session()->get('otp_phone'),
        ]);
    }

    public function verifyLoginOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6', 'regex:/^\d{6}$/'],
        ], [
            'otp.size'  => 'OTP must be exactly 6 digits.',
            'otp.regex' => 'OTP must contain digits only.',
        ]);

        $phone = $request->session()->get('otp_phone');
        $type  = $request->session()->get('otp_type');

        if (!$phone || $type !== 'login') {
            return redirect()->route('login')
                ->withErrors(['phone' => 'Session expired. Please try again.']);
        }

        $otpRecord = Otp::where('phone', $phone)
            ->where('otp', $request->otp)
            ->where('type', 'login')
            ->whereNull('used_at')
            ->latest()
            ->first();

        if (!$otpRecord || !$otpRecord->isValid()) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP. Please try again.']);
        }

        $user = User::where('phone', $phone)->first();

        if (!$user) {
            return redirect()->route('login')
                ->withErrors(['phone' => 'Account not found.']);
        }

        $guestSessionId = $request->session()->getId();

        $otpRecord->markAsUsed();
        $request->session()->forget(['otp_phone', 'otp_type']);

        Auth::login($user);

        // Migrate guest cart to user cart
        app(LoginController::class)->migrateGuestCart($guestSessionId, $user->id);

        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    // ─────────────────────────────────────────────
    //  REGISTER
    // ─────────────────────────────────────────────

    public function sendRegisterOtp(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'fname' => ['required', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'regex:/^[6-9]\d{9}$/', 'unique:users'],
        ], [
            'phone.regex'  => 'Please enter a valid 10-digit mobile number.',
            'phone.unique' => 'This phone number is already registered.',
            'email.unique' => 'This email address is already registered.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('login', ['tab' => 'register'])
                ->withErrors($validator)
                ->withInput()
                ->with('active_tab', 'register');
        }

        $phone = $request->phone;
        $otp   = $this->dispatchOtp($phone, 'register');

        $request->session()->put('register_data', [
            'fname' => $request->fname,
            'lname' => $request->lname,
            'email' => $request->email,
            'phone' => $phone,
        ]);
        $request->session()->put('otp_phone', $phone);
        $request->session()->put('otp_type', 'register');

        if (config('app.env') === 'local') {
            return redirect()->route('register.verify')
                ->with('dev_otp', $otp)
                ->with('info', "Development Mode — Your OTP is: <strong>{$otp}</strong>");
        }

        return redirect()->route('register.verify')
            ->with('success', 'OTP sent to your mobile number. Valid for 10 minutes.');
    }

    public function showRegisterVerify(Request $request)
    {
        if (!$request->session()->has('otp_phone')) {
            return redirect()->route('register');
        }

        return view('auth.otp-verify', [
            'type'  => 'register',
            'phone' => $request->session()->get('otp_phone'),
        ]);
    }

    public function verifyRegisterOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6', 'regex:/^\d{6}$/'],
        ], [
            'otp.size'  => 'OTP must be exactly 6 digits.',
            'otp.regex' => 'OTP must contain digits only.',
        ]);

        $phone        = $request->session()->get('otp_phone');
        $type         = $request->session()->get('otp_type');
        $registerData = $request->session()->get('register_data');

        if (!$phone || $type !== 'register' || !$registerData) {
            return redirect()->route('register')
                ->withErrors(['phone' => 'Session expired. Please try again.']);
        }

        $otpRecord = Otp::where('phone', $phone)
            ->where('otp', $request->otp)
            ->where('type', 'register')
            ->whereNull('used_at')
            ->latest()
            ->first();

        if (!$otpRecord || !$otpRecord->isValid()) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP. Please try again.']);
        }

        $user = User::create([
            'name'     => $registerData['fname'] . ' ' . $registerData['lname'],
            'email'    => $registerData['email'],
            'phone'    => $registerData['phone'],
            'password' => Hash::make(Str::random(32)),
        ]);

        $otpRecord->markAsUsed();
        $request->session()->forget(['otp_phone', 'otp_type', 'register_data']);

        Auth::login($user);
        $request->session()->regenerate();

        // Send email verification — non-blocking (queued)
        $user->sendEmailVerificationNotification();

        return redirect(route('home'));
    }

    // ─────────────────────────────────────────────
    //  RESEND OTP
    // ─────────────────────────────────────────────

    public function resendOtp(Request $request)
    {
        $phone = $request->session()->get('otp_phone');
        $type  = $request->session()->get('otp_type');

        if (!$phone || !$type) {
            return redirect()->route('login')
                ->withErrors(['phone' => 'Session expired. Please start over.']);
        }

        $otp = $this->dispatchOtp($phone, $type);

        $redirectRoute = $type === 'register' ? 'register.verify' : 'login.verify';

        if (config('app.env') === 'local') {
            return redirect()->route($redirectRoute)
                ->with('dev_otp', $otp)
                ->with('info', "Development Mode — Your new OTP is: <strong>{$otp}</strong>");
        }

        return redirect()->route($redirectRoute)
            ->with('success', 'A new OTP has been sent to your mobile number.');
    }
}
