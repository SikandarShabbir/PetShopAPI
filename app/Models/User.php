<?php

namespace App\Models;

use App\Common\HasUuid;
use App\Http\Requests\User\IndexRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use Firebase\JWT\JWT;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $uuid
 * @property string $first_name
 * @property string $last_name
 * @property int $is_admin
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $avatar
 * @property string $address
 * @property string $phone_number
 * @property int $is_marketing
 * @property string|null $last_login_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsMarketing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUuid($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable
        = [
            'uuid',
            'first_name',
            'last_name',
            'is_admin',
            'email',
            'email_verified_at',
            'password',
            'avatar',
            'address',
            'phone_number',
            'is_marketing',
            'last_login_at',
        ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden
        = [
            'password',
            'remember_token',
            'id',
            'avatar',
            'is_admin'
        ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts
        = [
            'email_verified_at' => 'datetime',
        ];

    public function password(): Attribute
    {
        return new Attribute(
            fn($value) => $value,
            fn($value) => bcrypt($value),
        );
    }

    public function storeUser(StoreRequest $request): array
    {
        $user = $this->create(
            $request->validated() + ['is_admin' => 1]
        );
        return [
            ...$user->toArray(),
            'token' => $user->refreshToken($request),
        ];
    }

    public function isAdmin(): Attribute
    {
        return new Attribute(
            fn($value) => ['user', 'admin'][$value]
        );
    }

    public function onlyUsers(): Builder
    {
        return $this->where('is_admin', 0);
    }

    public function getFilteredUsers(IndexRequest $request
    ): LengthAwarePaginator {
        return $this->onlyUsers()
            ->when(
                collect($this->getFillable())->contains($request->sortBy),
                fn($q) => $q->orderBy($request->sortBy)
            )
            ->when($request->desc, fn($q) => $q->orderBy('created_at', 'desc'))
            ->when(
                $request->first_name,
                fn($q) => $q->where(
                    'first_name',
                    'like',
                    '%'.$request->first_name.'%'
                )
            )
            ->when(
                $request->email,
                fn($q) => $q->where('email', 'like', '%'.$request->email.'%')
            )
            ->when(
                $request->phone,
                fn($q) => $q->where(
                    'phone_number',
                    'like',
                    '%'.$request->phone.'%'
                )
            )
            ->when(
                $request->address,
                fn($q) => $q->where(
                    'address',
                    'like',
                    '%'.$request->address.'%'
                )
            )
            ->when(
                $request->created_at,
                fn($q) => $q->whereDate('created_at', $request->created_at)
            )
            ->when(
                isset($request->marketing),
                fn($q) => $q->where('is_marketing', $request->marketing)
            )
            ->paginate($request->limit ? $request->limit : 10);
    }

    public function findAndUpdate(UpdateRequest $request, string $uuid): User
    {
        $user = $this->where('uuid', $uuid)->firstOrFail();
        $request->merge(['is_marketing' => $request->is_marketing ?? 0]);
        $user->update($request->all());
        return $user;
    }

    public function refreshToken(Request $request): string
    {
        return JWT::encode(
            [
                'user_uuid' => $this->uuid,
                'iss'       => $request->getSchemeAndHttpHost(),
                'exp'       => time() + 60 * 60 * 2,
            ],
            config('jwt.key'),
            'HS256'
        );
    }

}
