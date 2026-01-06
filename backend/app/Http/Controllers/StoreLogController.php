<?php

namespace App\Http\Controllers;

use App\Http\Requests\userLogRequest;
use App\Mail\otpMail;
use App\Models\email_otp;
use App\Models\refresh_token;
use App\Models\store;
use App\services\emailOtp;
use App\services\pathphoto;
use App\services\tokenReturn;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Socialite;

class StoreLogController extends Controller
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
            $last_user = store::select(['password', 'id', 'email_verified_at', 'email'])->where('email', $data['email'])->first();
            if ($last_user &&  $last_user->email_verified_at == null) {
                $last_user->delete();
            }
            $store = store::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            return $this->send($store);
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
            $store = store::select(['id', 'email', 'password', 'email_verified_at', 'name'])->where('email', $data['email'])->first();
            if (!$store || !Hash::check($data['password'], $store->password) || $store->email_verified_at == null) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'username atau password salah'
                ], 400);
            }
            $last_refresh_token = refresh_token::where("tokenable_id", $store->id)->where("tokenable_type", store::class);
            if ($last_refresh_token) {
                $last_refresh_token->delete();
            }
            $key = $this->getSecretKey();
            $payload = [
                'token' => 'store',
                'id' => $store->id,
                'name' => $store->name,
                'email' => $store->email,
                'exp' => now()->addMinutes(60)
            ];
            $access_token = JWT::encode($payload, $key, 'HS256');
            $refresh_token = $this->token_return->createRefreshToken($store);
            return $this->token_return->returnWithToken($store, $access_token, $refresh_token, false);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'server sedang error',
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $store = $request->store();
        $refresh_token = refresh_token::select(['id'])->where("tokenable_id", $store->id)->where("tokenable_type", store::class)->first();
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
            $store = $request->user();
            $path = $store->foto_profil;
            if ($request->hasFile("foto_profil")) {
                $file = $request->file('foto_profil');
                $path = $this->photofn->updatePathPhoto($path, $file, "users");
            }
            $store->update([
                'name' => $data['name'] ?? $store->name,
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
            $store = store::updateOrCreate(
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
                'token' => 'store',
                'id' => $store->id,
                'email' => $google->getEmail(),
                'name' => $store->name,
                'token' => Str::random(12),
                'exp' => now()->addMinutes(60)
            ];
            $last_refresh_token = refresh_token::where('tokenable_id', $store->id)->where('tokenable_type', store::class);
            if ($last_refresh_token) {
                $last_refresh_token->delete();
            }
            $key = $this->getSecretKey();
            $access_token = JWT::encode($payload, $key, 'HS256');
            $refresh_token = $this->token_return->createRefreshToken($store);
            return $this->token_return->returnWithToken($store, $access_token, $refresh_token, true);
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
            $refresh_token = refresh_token::where('tokenable_type', store::class)->where('token', hash('sha256', $cookie))->where('expires_at', '>', now())->first();
            if (!$refresh_token) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'sesi login telah habis silahkan login ulang'
                ], 401)->withCookie(cookie()->forget('refresh_token'));
            }
            $store = $refresh_token->tokenable;
            if ($store->email_verified_at == null) {
                $store->delete();
                return response()->json([], 401);
            }
            $payload = [
                'id' => $store->id,
                'email' => $store->email,
                'name' => $store->name,
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
        return response()->json($request->store());
    }

    protected function send($store)
    {
        $otp = $this->emailFn->createOtp($store->email, $store);
        Mail::to($store->email)->send(new otpMail($otp));
        return response()->json([
            'status' => 'success',
            'message' => 'OTP berhasil dikirim ke email',
            'email' => $store->email
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
            $email = email_otp::where('otp', $otp)->where('for_type', store::class)->where('expires_at', '>', now())->first();
            if (!$email) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'kode otp salah',
                ], 401);
            }
            $store = $email->for;
            $email->delete();
            $store->update([
                'email_verified_at' => now(),
            ]);
            $store->save();
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
