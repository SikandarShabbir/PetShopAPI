<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param  string  $uuid
     *
     * @return void|never
     */
    public function validateAdmin(string $uuid)
    {
        if ($this->user->where('uuid', $uuid)->value('is_admin') === 'admin') {
            $this->throwHttpResponseException(
                'Unauthorized: Not enough privileges'
            );
        }
    }

    /**
     * @param  string  $uuid
     *
     * @return void|never
     */
    public function checkUserExistence(string $uuid)
    {
        if ($this->user->where('uuid', $uuid)->doesntExist()) {
            $this->throwHttpResponseException(
                'User not found'
            );
        }
    }

    /**
     * @param  Request  $request
     *
     * @return array<string,string>|void
     */
    public function authenticateUser(Request $request)
    {
        $user = $this->user->where('email', '=', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                return [
                    'token' => $user->refreshToken($request),
                ];
            }
        }
        $this->throwHttpResponseException(
            'Failed to authenticate user'
        );
    }

    public function throwHttpResponseException(
        string $error
    ): never {
        throw new HttpResponseException(
            response()->json(
                [
                    "success" => 0,
                    "data"    => [],
                    "error"   => $error,
                    "errors"  => [],
                    "trace"   => [],
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            )
        );
    }
}
