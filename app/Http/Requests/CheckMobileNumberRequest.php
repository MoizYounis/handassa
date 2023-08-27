<?php

namespace App\Http\Requests;

use App\Abstracts\FormRequest;

class CheckMobileNumberRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'mobile_number' => 'required|string|unique:users,mobile_number'
        ];
    }
}
