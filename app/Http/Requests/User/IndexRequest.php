<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class IndexRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'page'       => 'sometimes|integer',
            'limit'      => 'sometimes|integer',
            'sortBy'     => 'sometimes|string',
            'desc'       => 'sometimes|boolean',
            'first_name' => 'sometimes|string',
            'email'      => 'sometimes|string',
            'phone'      => 'sometimes|string',
            'address'    => 'sometimes|string',
            'created_at' => 'sometimes|string',
            'marketing'  => 'sometimes|string',
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
