<?php

namespace App\Http\Requests;

use App\Helpers\Constant;
use App\Abstracts\FormRequest as FormRequest;

class PostStatusRequest extends FormRequest
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
            "id" => 'required|integer|exists:posts,id',
            "status" => "required|string|in:" . Constant::COMPLETED . ',' . Constant::ENDED
        ];
    }

    public function prepareRequest()
    {
        $request = $this;
        return [
            "id" => $request['id'],
            "status" => $request["status"]
        ];
    }
}
