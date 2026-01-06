<?php

namespace App\Http\Controllers;

use App\Http\Requests\userLogRequest;
use App\Mail\otpMail;
use App\Models\email_otp;
use App\Models\refresh_token;
use App\Models\User;
use App\services\emailOtp;
use App\services\pathphoto;
use App\services\tokenReturn;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

use function Illuminate\Support\now;

class userLogController extends Controller
{
    public function __construct(protected tokenReturn $token_return, protected pathphoto $photofn, protected emailOtp $emailFn) {}
    protected function getSecretKey()
    {
        $key = config('passport.firebase_key');
        return $key;
    }

    public function register(userLogRequest $request)
    {
        try {
            $data = $request->validated();
            $last_user = User::select(['password', 'id', 'email_verified_at', 'email'])->where('email', $data['email'])->first();
            if ($last_user &&  $last_user->email_verified_at == null) {
                $last_user->delete();
            }
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            return $this->send($user);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'server sedang error',
            ], 500);
        }
    }

    public function login(userLogRequest $request)
    {
        try {
            $data = $request->validated();
            $user = User::select(['id', 'email', 'password', 'email_verified_at', 'name'])->where('email', $data['email'])->first();
            if (!$user || !Hash::check($data['password'], $user->password) || $user->email_verified_at == null) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'username atau password salah'
                ], 400);
            }
            $last_refresh_token = refresh_token::where("tokenable_id", $user->id)->where("tokenable_type", User::class);
            if ($last_refresh_token) {
                $last_refresh_token->delete();
            }
            $key = $this->getSecretKey();
            $payload = [
                'token' => 'user',
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'exp' => now()->addMinutes(60)
            ];
            $access_token = JWT::encode($payload, $key, 'HS256');
            $refresh_token = $this->token_return->createRefreshToken($user);
            return $this->token_return->returnWithToken($user, $access_token, $refresh_token, false);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'server sedang error',
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $refresh_token = refresh_token::select(['id'])->where("tokenable_id", $user->id)->where("tokenable_type", User::class)->first();
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
        try {
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
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'server sedang error',
            ], 500);
        }
    }

    public function redirect()
    {
        return Socialite::driver('google')->with(['prompt' => 'consent', 'access_type' => 'offline'])->redirect();
    }

    public function callback()
    {
        try {
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
                'token' => 'user',
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
            $refresh_token = $this->token_return->createRefreshToken($user);
            return $this->token_return->returnWithToken($user, $access_token, $refresh_token, true);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'server sedang error',
            ], 500);
        }
    }

    public function refresh(Request $request)
    {
        try {
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
            $user = $refresh_token->tokenable;
            if ($user->email_verified_at == null) {
                $user->delete();
                return response()->json([], 401);
            }
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
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'server sedang error',
            ], 500);
        }
    }

    public function profile(Request $request)
    {
        return response()->json($request->user());
    }

    protected function send($user)
    {
        $otp = $this->emailFn->createOtp($user->email, $user);
        Mail::to($user->email)->send(new otpMail($otp));
        return response()->json([
            'status' => 'success',
            'message' => 'OTP berhasil dikirim ke email',
            'email' => $user->email
        ]);
    }

    public function verifyedOtp(Request $request)
    {
        try {
            $rule = [
                'otp' => ['string', 'min:6', 'max:6'],
            ];
            $message = [
                'otp.string' => 'otp harus berupa string',
                'otp.min' => 'minimal otp :min',
                'otp.max' => 'maximal otp :max',
            ];
            $validator = validator($request->all(), $rule, $message);
            $data = $validator->validated();
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'validation error',
                    'error' => $validator->errors()
                ], 400);
            }
            $otp = hash('sha256', $data['otp']);
            $email = email_otp::where('otp', $otp)->where('for_type', User::class)->where('expires_at', '>', now())->first();
            if (!$email) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'kode otp salah',
                ], 401);
            }
            $user = $email->for;
            $email->delete();
            $user->update([
                'email_verified_at' => now(),
            ]);
            $user->save();
            return response()->json([
                'status' => 'success',
                'message' => 'berhasil register'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'server sedang error',
            ], 500);
        }
    }
}
