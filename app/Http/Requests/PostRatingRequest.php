<?php

namespace App\Http\Requests;

use App\Helpers\Constant;
use App\Abstracts\FormRequest as FormRequest;

class PostRatingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "post_id" => 'required|integer|exists:posts,id',
            "rating" => "required|numeric",
            "review" => "required|string"
        ];
    }

    public function prepareRequest($role)
    {
        $request = $this;
        return [
            "post_id" => $request['post_id'],
            "rating" => $request["rating"],
            "review" => $request["review"],
            "role" => $role
        ];
    }
}
