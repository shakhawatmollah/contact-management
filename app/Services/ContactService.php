<?php

namespace App\Services;

use App\Models\Contact;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ContactService
{
    public function createContact(array $data): Contact
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
            'source_website' => 'nullable|url',
            'ip_address' => 'nullable|ip',
            'metadata' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        try {
            return Contact::create($validator->validated());
        } catch (\Exception $e) {
            Log::error('Failed to create contact: ' . $e->getMessage());
            throw $e;
        }
    }
}
