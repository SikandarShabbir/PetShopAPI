<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use UnexpectedValueException;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @param $userType
     *
     * @return JsonResponse
     */
    public function handle(Request $request, Closure $next, string $userType)
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

                if (auth('api')->user() ?->is_admin === $userType) {
                    return $next($request);
                }
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
        } catch (UnexpectedValueException $exception) {
            return response()->json(
                [
                    'success' => 0,
                    'data'    => [],
                    'error'   => 'Token Not Provided!',
                    'errors'  => [],
                    'trace'   => $exception
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
