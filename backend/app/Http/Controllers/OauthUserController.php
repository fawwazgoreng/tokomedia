<?php

namespace App\Http\Controllers;

use App\Http\Requests\userLogRequest;
use App\Mail\otpMail;
use App\Models\email_otp;
use App\Models\refresh_token;
use App\Models\User;
use App\services\emailOtp;
use App\services\pathphoto;
use App\services\resReturn;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

use function Illuminate\Support\now;

class OauthUserController extends Controller
{
    public function __construct(protected resReturn $res_return, protected pathphoto $photofn, protected emailOtp $emailFn) {}

    protected function getSecretKey()
    {
        $key = env("FIREBASE_SECRET_KEY", "gdwyudabdsjab");
        return $key;
    }

    public function register(userLogRequest $request)
    {
        $data = $request->validated();
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'berhasil register'
        ]);
    }

    public function login(userLogRequest $request)
    {
        $data = $request->validated();
        $user = User::where('email', $data['email']);
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'username atau password salah'
            ]);
        }
        $key = $this->getSecretKey();
        $payload = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'exp' => now()->addMinutes(60)
        ];
        $access_token = JWT::encode($payload, $key, 'HS256');
        $refresh_token = $this->res_return->createRefreshToken($user);
        return $this->res_return->returnWithToken($user, $access_token, $refresh_token);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $refresh_token = refresh_token::where("tokenable_id", $user->id)->where("tokenable_type", User::class)->first();
        if ($refresh_token) {
            $refresh_token->delete();
        }
        return response()->json([
            'status' => 'success',
            'message' => 'berhasil logout'
        ], 200)->withCookie(cookie()->forget("refresh_token"));
    }

    public function update(userLogRequest $request)
    {
        $data = $request->only('name', 'foto_profil');
        $user = $request->user();
        $path = $user->foto_profil;
        if ($request->hasFile("foto_profil")) {
            $file = $request->file('foto_profil');
            $path = $this->photofn->updatePathPhoto($path, $file, "users");
        }
        $user->update([
            'name' => $data['name'] ?? $user->name,
            'foto_profil' => $path,
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'berhasil update profil'
        ]);
    }

    public function redirect()
    {
        return Socialite::driver('google')->with(['prompt' => 'consent', 'access_type' => 'offline'])->redirect();
    }

    public function callback()
    {
        $google = Socialite::driver('google')->user();
        $user = User::updateOrCreate(
            [
                'email' => $google->getEmail(),
            ],
            [
                'name' => $google->getName(),
                'provider' => 'google',
                'provider_id' => $google->getId(),
                'foto_profil' => $google->getAvatar() ?? 'kosong',
                'password' => Hash::make(Str::random(12))
            ]
        );
        $payload = [
            'id' => $user->id,
            'email' => $google->getEmail(),
            'name' => $user->name,
            'token' => Str::random(12),
            'exp' => now()->addMinutes(60)
        ];
        $last_refresh_token = refresh_token::where('tokenable_id', $user->id)->where('tokenable_type', User::class);
        if ($last_refresh_token) {
            $last_refresh_token->delete();
        }
        $key = $this->getSecretKey();
        $access_token = JWT::encode($payload, $key, 'HS256');
        $refresh_token = $this->res_return->createRefreshToken($user);
        return $this->res_return->returnWithToken($user, $access_token, $refresh_token, true);
    }

    public function refresh(Request $request)
    {
        $cookie = $request->cookie('refresh_token');
        if (!$cookie) {
            return response()->json([
                'status' => 'error',
                'message' => 'sesi login telah habis silahkan login ulang'
            ], 401);
        }
        $refresh_token = refresh_token::where('tokenable_type', User::class)->where('token', hash('sha256', $cookie))->where('expires_at', '>', now())->first();
        if (!$refresh_token) {
            return response()->json([
                'status' => 'error',
                'message' => 'sesi login telah habis silahkan login ulang'
            ], 401)->withCookie(cookie()->forget('refresh_token'));
        }
        Log::info($refresh_token);
        $user = $refresh_token->tokenable;
        $payload = [
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'token' => Str::random(12),
            'exp' => now()->addMinutes(60)
        ];
        $key = $this->getSecretKey();
        $access_token = JWT::encode($payload, $key, 'HS256');
        return response()->json([
            'token' => $access_token
        ], 200);
    }

    public function profile(Request $request)
    {
        return response()->json($request->user());
    }

    protected function send($email)
    {
        $otp = $this->emailFn->createOtp($email);
        Mail::to($email)->send(new otpMail($otp));
        return response()->json([
            'message' => 'OTP berhasil dikirim ke email'
        ]);
    }

    public function verifyedOtp(Request $request)
    {
        $rule = [
            'kode' => ['string', 'min:6', 'max:6'],
            'email' => ['email']
        ];
        $message = [
            'kode.string' => 'kode harus berupa string',
            'kode.min' => 'minimal kode :min',
            'kode.max' => 'maximal kode :max',
            'email.email' => 'maximal kode :email',
        ];
        $validator = validator($request->all(), $rule, $message);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'validation error',
                'error' => $validator->errors()
            ], 400);
        }
        $email = email_otp::select(['id', 'email', 'expires_at', 'otp'])->where('email', $validator['email']);
        if (!$email || !Hash::check($validator['email'], $email)) {
            return response()->json([
                'status' => 'error',
                'message' => 'kode otp salah',
            ], 401);
        }
        $email->delete();
        
    }
}
