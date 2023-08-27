<?php

namespace App\Http\Requests;

use App\Abstracts\FormRequest;
use App\Helpers\Constant;

class RegisterRequest extends FormRequest
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
            'role' => "required|string|in:" . Constant::CLIENT . ',' . Constant::PROFESSIONAL,
            'type' => "required|string|in:" . Constant::PERSON . ',' . Constant::FREELANCER . ',' . Constant::COMPANY,
            'image' => 'string|image|mimes:jpg,jpeg,png|max:255',
            'username' => 'required|string|regex:/^\S*$/u|max:255|unique:users,username',
            'name' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:255|unique:users,mobile_number',
            'phone_number' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255|exists:locations,location',
            'cr_copy' => 'string|image|mimes:jpg,jpeg,png|max:255|required_if:type,' . Constant::COMPANY,
            'id_copy' => 'string|image|mimes:jpg,jpeg,png|max:255|required_if:type,' . Constant::FREELANCER,
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'username.regex' => "username does not allow spaces."
        ];
    }

    public function prepareRequest()
    {
        $request = $this;
        return [
            'role' => $request['role'],
            'type' => $request['type'],
            'image' => $request['image'],
            'username' => $request['username'],
            'name' => $request['name'],
            'mobile_number' => $request['mobile_number'],
            'phone_number' => $request['phone_number'],
            'location' => $request['location'],
            'cr_copy' => $request['cr_copy'],
            'id_copy' => $request['id_copy'],
        ];
    }
}
