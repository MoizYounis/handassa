<?php

namespace App\Http\Requests;

use App\Helpers\Constant;
use App\Abstracts\FormRequest;

class ProfileRequest extends FormRequest
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
            'experience' => 'nullable|integer',
            'total_project' => 'nullable|integer',
            'project_done_by_app' => 'nullable|integer',
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255|exists:locations,location',
            'services' => 'string|required_if:role,' . Constant::PROFESSIONAL,
            'categories' => 'string|required_if:role,' . Constant::PROFESSIONAL
        ];

        return $rules;
    }

    public function prepareRequest()
    {
        $request = $this;
        return [
            'experience' => $request['experience'],
            'total_project' => $request['total_project'],
            'project_done_by_app' => $request['project_done_by_app'],
            'image' => $request['image'],
            'name' => $request['name'],
            'phone_number' => $request['phone_number'],
            'location' => $request['location'],
            'services' => explode(',', $request['services']),
            'categories' => explode(',', $request['categories']),
            'cr_copy' => $request['cr_copy'],
            'id_copy' => $request['id_copy'],
            'project_images' => $request['project_images']
        ];
    }
}
