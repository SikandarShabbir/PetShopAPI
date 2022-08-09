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

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="PetShop OpenApi Demo Documentation",
 *      description="L5 Swagger OpenApi description",
 *      @OA\Contact(
 *          email="sikandarshabbir11@gmail.com"
 *      )
 * )
 *
 * @OA\Tag(
 *     name="Admin",
 *     description="Admin API endpoints"
 * )
 *
 * @OA\SecurityScheme(
 * securityScheme="bearerAuth",
 * type="http",
 * scheme="bearer",
 * bearerFormat="JWT"
 * ),
 */
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

    /**
     * @OA\Post(
     *      path="/api/v1/admin/login",
     *      tags={"Admin"},
     *      summary="Login an Admin Account",
     *      operationId="adminLogin",
     *     @OA\RequestBody(
     *     required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *               type="object",
     *               required={"email", "password"},
     *               @OA\Property(property="email", type="email", description="Admin email"),
     *               @OA\Property(property="password", type="password", description="Admin password")
     *            ),
     *         )
     *      ),
     *
     * @OA\Response(
     *          response=200,
     *          description="OK",
     *      ),
     * @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *      ),
     * @OA\Response(
     *          response=404,
     *          description="Page not found",
     *      ),
     * @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *      ),
     * @OA\Response(
     *          response=500,
     *          description="Internal server error",
     *      ),
     *
     * @OA\PathItem (
     *     ),
     * )
     * @param  LoginRequest  $request
     *
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $data = $this->userService->authenticateUser($request);

        return $this->userResource($data);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/admin/logout",
     *      tags={"Admin"},
     *      summary="Logout an Admin Account",
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *      ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *      ),
     *     @OA\Response(
     *          response=404,
     *          description="Page not found",
     *      ),
     *     @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *      ),
     *     @OA\Response(
     *          response=500,
     *          description="Internal server error",
     *      ),
     *     @OA\PathItem (
     *     ),
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        return $this->userResource(
            []
        );
    }

    /**
     * @OA\Post(
     *      path="/api/v1/admin/create",
     *      tags={"Admin"},
     *      summary="Create an Admin Account",
     *     operationId="adminStore",
     *     @OA\RequestBody(
     *     required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *               type="object",
     *               required={"first_name","last_name","email","password","password_confirmation","avatar","address","phone_number"},
     *               @OA\Property(property="first_name", type="string", description="User firstname"),
     *               @OA\Property(property="last_name", type="string", description="User lastname"),
     *               @OA\Property(property="email", type="string", description="User email"),
     *               @OA\Property(property="password", type="string", description="User password"),
     *               @OA\Property(property="password_confirmation", type="string", description="User password"),
     *               @OA\Property(property="avatar", type="string", description="Avatar image UUID"),
     *               @OA\Property(property="address", type="string", description="User main address"),
     *               @OA\Property(property="phone_number", type="string", description="User main phone number"),
     *               @OA\Property(property="marketing", type="string", description="User marketing preferences"),
     *            ),
     *         )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *      ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *      ),
     *     @OA\Response(
     *          response=404,
     *          description="Page not found",
     *      ),
     *     @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *      ),
     *     @OA\Response(
     *          response=500,
     *          description="Internal server error",
     *      ),
     *     @OA\PathItem (
     *     ),
     * )
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $data = $this->user->storeUser($request);

        return $this->userResource($data);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/admin/user-listing",
     *      security={ {"bearerAuth": {} }},
     *      summary="List all user accounts",
     *      tags={"Admin"},
     *      @OA\Parameter(
     *      name="page",
     *      in="query",
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *     @OA\Parameter(
     *      name="limit",
     *      in="query",
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *     @OA\Parameter(
     *      name="sortBy",
     *      in="query",
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *     @OA\Parameter(
     *      name="desc",
     *      in="query",
     *      @OA\Schema(
     *           type="boolean"
     *      )
     *   ),
     *     @OA\Parameter(
     *      name="first_name",
     *      in="query",
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *     @OA\Parameter(
     *      name="email",
     *      in="query",
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *     @OA\Parameter(
     *      name="phone",
     *      in="query",
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *     @OA\Parameter(
     *      name="address",
     *      in="query",
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *     @OA\Parameter(
     *      name="created_at",
     *      in="query",
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *     @OA\Parameter(
     *      name="marketing",
     *      in="query",
     *      @OA\Schema(
     *           type="string",
     *           enum={0, 1}
     *      )
     *   ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *      ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *      ),
     *     @OA\Response(
     *          response=404,
     *          description="Page not found",
     *      ),
     *     @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *      ),
     *     @OA\Response(
     *          response=500,
     *          description="Internal server error",
     *      ),
     *     @OA\PathItem (
     *     ),
     * )
     * /
     * /**
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

    /**
     * @OA\Put(
     *      path="/api/v1/admin/user-edit/{uuid}",
     *      security={ {"bearerAuth": {} }},
     *      tags={"Admin"},
     *      summary="Edit user accounts",
     *
     *      @OA\Parameter(
     *      name="uuid",
     *      required=true,
     *      in="path",
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *
     *     operationId="adminUpdate",
     *     @OA\RequestBody(
     *     required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *               type="object",
     *               required={"first_name","last_name","email","password","password_confirmation","address","phone_number"},
     *               @OA\Property(property="first_name", type="string", description="User firstname"),
     *               @OA\Property(property="last_name", type="string", description="User lastname"),
     *               @OA\Property(property="email", type="string", description="User email"),
     *               @OA\Property(property="password", type="string", description="User password"),
     *               @OA\Property(property="password_confirmation", type="string", description="User password"),
     *               @OA\Property(property="avatar", type="string", description="Avatar image UUID"),
     *               @OA\Property(property="address", type="string", description="User main address"),
     *               @OA\Property(property="phone_number", type="string", description="User main phone number"),
     *               @OA\Property(property="is_marketing", type="string", description="User marketing preferences"),
     *            ),
     *         )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *      ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *      ),
     *     @OA\Response(
     *          response=404,
     *          description="Page not found",
     *      ),
     *     @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *      ),
     *     @OA\Response(
     *          response=500,
     *          description="Internal server error",
     *      ),
     *     @OA\PathItem (
     *     ),
     * )
     */
    public function update(UpdateRequest $request, string $uuid): JsonResponse
    {
        $this->userService->validateAdmin($uuid);

        $this->userService->checkUserExistence($uuid);

        $user = $this->user->findAndUpdate($request, $uuid);

        return $this->userResource([$user]);
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/admin/user-delete/{uuid}",
     *      security={ {"bearerAuth": {} }},
     *      tags={"Admin"},
     *      summary="Delete user accounts",
     *
     *      @OA\Parameter(
     *      name="uuid",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *      ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *      ),
     *     @OA\Response(
     *          response=404,
     *          description="Page not found",
     *      ),
     *     @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *      ),
     *     @OA\Response(
     *          response=500,
     *          description="Internal server error",
     *      ),
     *     @OA\PathItem (
     *     ),
     * )
     */
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
