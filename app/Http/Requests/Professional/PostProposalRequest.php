<?php

namespace App\Http\Requests\Professional;

use App\Abstracts\FormRequest as FormRequest;

class PostProposalRequest extends FormRequest
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
            "price" => 'required|numeric',
            "description" => 'required|string'
        ];
    }

    public function prepareRequest()
    {
        $request = $this;
        return [
            "post_id" => $request['post_id'],
            "price" => $request['price'],
            "description" => $request['description']
        ];
    }
}
