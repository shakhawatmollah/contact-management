<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WebsiteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'url' => [
                'required',
                'string',
                'url',
                'max:255',
                Rule::unique('websites')->ignore($this->website),
            ],
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'regenerate_api_key' => 'sometimes|boolean',
        ];
    }
}
