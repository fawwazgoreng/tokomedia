<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use stdClass;
use Symfony\Component\HttpFoundation\Response;

class userAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // try {
            $header = $request->bearerToken();
            $key = env('FIREBASE_SECRET_KEY', "gdwyudabdsjab");
            $stdclass = new stdClass();
            $access_token = JWT::decode($header , new Key($key , 'HS256') , $stdclass);
            $request->setUserResolver(fn() => $access_token);
            return $next($request);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'status' => 'failed to login'
        //     ], 401);
        // }
    }
}
