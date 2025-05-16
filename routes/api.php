<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ContactApiController;

Route::prefix('v1')->middleware(['api.secure'])->group(function () {
    Route::post('/contacts', [ContactApiController::class, 'store'])
       // ->middleware(['api', 'throttle:60,1'])
        ->middleware('throttle.contacts:5,1')
        ->name('api.contact.store');
});
