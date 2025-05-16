<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\EmailController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

//\Illuminate\Support\Facades\Auth::routes();

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout'); // This defines the named route 'logout'

Route::get('/test', function () {
    return  "Hi, Shakhawat";// view('welcome');
})->withoutMiddleware(['api.secure', 'throttle.contacts'])->name('test');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');

Route::middleware(['auth', 'verified'])->group(function () {
    // Contact routes
    Route::get('/contacts/{contact}', [ContactController::class, 'show'])->name('contacts.show');

    // Email routes
    Route::get('/emails', [EmailController::class, 'index'])->name('emails.index');
    Route::get('/emails/{email}', [EmailController::class, 'show'])->name('emails.show');
    Route::get('/contacts/{contact}/reply', [EmailController::class, 'reply'])->name('emails.reply');
    Route::post('/contacts/{contact}/reply', [EmailController::class, 'sendReply'])->name('emails.send-reply');
});

