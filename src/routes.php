<?php
// Even though it seems that the User is not used here in the callback function, we need to have it so that Laravel can resolve the user variable in the path

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

if (config('spa.check_email_exists_endpoint')) {
    Route::middleware('web')->post(config('spa.route_paths.email_exists'), function (Request $request) {
        $request->validate([
            'email' => 'required|email',
        ]);
        User::where('email', $request->email)->firstOrFail();
        return response()->noContent(200);
    });
}

Route::middleware('api', 'auth:sanctum')->prefix('api')->group(function () {
    Route::get('user', function (Request $request) {
        return $request->user();
    });
});
