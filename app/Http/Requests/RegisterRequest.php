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
        $rules = [
            'role' => "required|string|in:" . Constant::CLIENT . ',' . Constant::PROFESSIONAL,
            'type' => "required|string|in:" . Constant::PERSON . ',' . Constant::COMPANY,
            'username' => 'required|string|regex:/^\S*$/u|max:255|unique:users,username',
            'name' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:255|unique:users,mobile_number',
            'phone_number' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255|exists:locations,location',
            'services' => 'array|exists:services,id|required_if:role,' . Constant::PROFESSIONAL,
            'categories' => 'array|exists:categories,id|required_if:role,' . Constant::PROFESSIONAL,
            'cr_copy' => 'required_if:type,' . Constant::COMPANY
        ];

        // Check if the user role is professional and type is person
        if ($this->input('role') === Constant::PROFESSIONAL && $this->input('type') === Constant::PERSON) {
            // Add the rule for the id_copy field to be required
            $rules['id_copy'] = 'required';
        }

        return $rules;
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
            'services' => $request['services'],
            'categories' => $request['categories'],
            'cr_copy' => $request['cr_copy'],
            'id_copy' => $request['id_copy'],
            'project_images' => $request['project_images']
        ];
    }
}
