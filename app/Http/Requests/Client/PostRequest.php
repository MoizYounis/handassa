<?php

namespace App\Http\Requests\Client;

use App\Abstracts\FormRequest as FormRequest;

class PostRequest extends FormRequest
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
            "service_id" => 'required|integer|exists:services,id',
            "category_id" => 'required|integer|exists:categories,id',
            "title" => 'required|string|max:255',
            "description" => 'required|string',
            "image" => 'required|string'
        ];
    }

    public function prepareRequest()
    {
        $request = $this;
        return [
            "service_id" => $request['service_id'],
            "category_id" => $request['category_id'],
            "title" => $request['title'],
            "description" => $request['description'],
            "image" => $request['image']
        ];
    }
}
