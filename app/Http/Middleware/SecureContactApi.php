<?php

namespace App\Http\Middleware;

use App\Models\Website;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecureContactApi
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!in_array($request->header('origin'), config('contact.api.allowed_origins'))) {
            // Optional: Verify API key for external submissions
            if ($request->header('X-API-KEY') !== config('contact.api.key')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized request origin'
                ], 403);
            }

            // Check if the API key is valid for the website
            if (!Website::where('api_key', $request->header('X-API-KEY'))->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid API key'
                ], 403);
            }
        }

        if (!$request->isJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Content-Type must be application/json',
                'allowed_content_types' => 'application/json'
            ], 415);
        }

        return $next($request);
    }

}
