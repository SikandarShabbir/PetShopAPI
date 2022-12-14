<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'first_name'            => 'required|string',
            'last_name'             => 'required|string',
            'email'                 => 'required|email|max:255|unique:users,email',
            'password'              => 'required|string|min:8',
            'password_confirmation' => 'required|string|same:password',
            'avatar'                => 'required|string|max:36',
            'address'               => 'required|string',
            'phone_number'          => 'required|string',
            'marketing'             => 'sometimes',
        ];
    }


    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(
                [
                    "success" => 0,
                    "data"    => [],
                    "error"   => 'Failed Validation',
                    "errors"  => $validator->errors(),
                    "trace"   => []
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            )
        );
    }
}
