<?php

namespace App\Http\Requests;

use App\Abstracts\FormRequest;

class CheckUsernameRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'username' => 'required|string|regex:/^\S*$/u|max:255|unique:users,username'
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
}
