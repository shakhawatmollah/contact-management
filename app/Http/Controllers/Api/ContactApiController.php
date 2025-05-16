<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiContactRequest;
use App\Services\ContactService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class ContactApiController extends Controller
{
    protected $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    public function store(ApiContactRequest $request): \Illuminate\Http\JsonResponse
    {
      //  dd($request->header('X-API-KEY'), config('contact.api.key'));
       // dd($request->ip());
        // Rate limiting by IP and email combination
        $rateLimitKey = 'contact-api:'.$request->ip().'|'.$request->email;

        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            return response()->json([
                'message' => 'Too many submissions. Please try again later.',
                'retry_after' => RateLimiter::availableIn($rateLimitKey)
            ], 429);
        }

        RateLimiter::hit($rateLimitKey, 300); // 5 attempts per 5 minutes

        try {
            $data = array_merge($request->validated(), [
                'ip_address' => $request->ip(),
                'source_website' => $request->header('origin', $request->source_website)
            ]);

            $contact = $this->contactService->createContact($data);

            return response()->json([
                'success' => true,
                'message' => 'Contact submission received',
                'data' => [
                    'contact_id' => $contact->id,
                    'name' => $contact->name,
                    'email' => $contact->email,
                    'created_at' => $contact->created_at->toISOString()
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process contact form',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

//    public function store(ContactFormRequest $request)
//    {
//        $executed = RateLimiter::attempt(
//            'contact-form:'.$request->ip(),
//            $perMinute = 5,
//            function() {}
//        );
//
//        if (!$executed) {
//            return response()->json([
//                'message' => 'Too many submissions. Please try again later.'
//            ], 429);
//        }
//
//        $data = $request->validated();
//        $data['ip_address'] = $request->ip();
//        $data['source_website'] = $request->header('origin', 'unknown');
//
//        try {
//            $contact = $this->contactService->createContact($data);
//            return response()->json([
//                'message' => 'Contact form submitted successfully',
//                'data' => $contact
//            ], 201);
//        } catch (\Exception $e) {
//            return response()->json([
//                'message' => 'Failed to process contact form',
//                'error' => $e->getMessage()
//            ], 500);
//        }
//    }
}
