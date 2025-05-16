<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApiContactRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (substr_count($value, '@') > 1) {
                        $fail('The '.$attribute.' contains multiple @ symbols.');
                    }
                }
            ],
            'phone' => 'nullable|string|max:20|regex:/^[\+\d\s\-\(\)]{7,20}$/',
            'message' => 'required|string|min:10|max:5000',
            'source_website' => 'nullable|url|max:255',
            'metadata' => 'nullable|array',
            'metadata.*' => 'nullable|string|max:255',

            // Optional honeypot field
            'honeypot' => 'nullable|string|max:0' // Must be empty
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'The phone number format is invalid.',
            'honeypot.max' => 'This form submission was detected as spam.'
        ];
    }

    protected function prepareForValidation(): void
    {
        // Clean phone number if provided
        if ($this->phone) {
            $this->merge([
                'phone' => preg_replace('/[^+\d]/', '', $this->phone)
            ]);
        }
    }
}
