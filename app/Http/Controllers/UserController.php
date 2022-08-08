<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\IndexRequest;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\UserCollection;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * @var UserService
     */
    public UserService $userService;
    protected User $user;

    public function __construct(User $user, UserService $userService)
    {
        $this->user = $user;
        $this->userService = $userService;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $this->userService->authenticateUser($request);

        return $this->userResource($data);
    }

    public function logout(Request $request): JsonResponse
    {
        return $this->userResource(
            []
        );
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $data = $this->user->storeUser($request);

        return $this->userResource($data);
    }

    /**
     * @param  IndexRequest  $request
     *
     * @return UserCollection
     */
    public function index(IndexRequest $request): UserCollection
    {
        return new UserCollection(
            $this->user->getFilteredUsers($request)
        );
    }

    public function update(UpdateRequest $request, string $uuid): JsonResponse
    {
        $this->userService->validateAdmin($uuid);

        $this->userService->checkUserExistence($uuid);

        $user = $this->user->findAndUpdate($request, $uuid);

        return $this->userResource([$user]);
    }

    public function destroy(string $uuid): JsonResponse
    {
        $this->userService->validateAdmin($uuid);
        $this->userService->checkUserExistence($uuid);
        $this->user->where('uuid', $uuid)->delete();
        return $this->userResource([]);
    }

    protected function userResource(array $data): JsonResponse
    {
        return response()->json(
            [
                "success" => 1,
                "data"    => $data,
                "error"   => null,
                "errors"  => [],
                "extra"   => []
            ],
            Response::HTTP_OK
        );
    }
}
