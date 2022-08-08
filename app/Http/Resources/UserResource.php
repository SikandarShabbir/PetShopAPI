<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public static $wrap = '';

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     *
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'uuid'              => $this->uuid,
            'first_name'        => $this->first_name,
            'last_name'         => $this->last_name,
            'email'             => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'avatar'            => $this->avatar,
            'address'           => $this->address,
            'phone_number'      => $this->phone_number,
            'is_marketing'      => $this->is_marketing,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
            'last_login_at'     => $this->last_login_at,
        ];
    }
}
