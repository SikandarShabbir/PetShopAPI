<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            if ($request->bearerToken()) {
                JWT::decode(
                    $request->bearerToken(),
                    new Key(
                        config('jwt.key'),
                        'HS256'
                    )
                );
                return $next($request);
            }
            return response()->json(
                [
                    'success' => 0,
                    'data'    => [],
                    'error'   => 'Unauthorized',
                    'errors'  => [],
                    'trace'   => []
                ],
                Response::HTTP_UNAUTHORIZED
            );
        } catch (ExpiredException $expiredException) {
            return response()->json(
                [
                    'status' => 0,
                    'data'   => [],
                    'error'  => 'Token Expired',
                    'errors' => [],
                    'trace'  => $expiredException
                ],
                Response::HTTP_UNAUTHORIZED
            );
        } catch (\Exception $exception) {
            return response()->json(
                [
                    'success' => 0,
                    'data'    => [],
                    'error'   => 'Something went wrong!',
                    'errors'  => [],
                    'trace'   => $exception
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
