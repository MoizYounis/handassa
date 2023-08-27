<?php

namespace App\Http\Requests;

use App\Abstracts\FormRequest;
use App\Helpers\Constant;

class LoginRequest extends FormRequest
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
            'mobile_number' => 'required|string|max:255|exists:users,mobile_number'
        ];
    }

    public function prepareRequest()
    {
        $request = $this;
        return [
            'mobile_number' => $request['mobile_number']
        ];
    }
}
