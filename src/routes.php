<?php
// Even though it seems that the User is not used here in the callback function, we need to have it so that Laravel can resolve the user variable in the path

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Mmedia\LaravelSpa\LaravelSpaFacade;

Route::get('login', function () {
    return redirect(
        LaravelSpaFacade::getSpaUrlForPath('login')
    );
})->name('login');

Route::get('register', function () {
    return redirect(
        LaravelSpaFacade::getSpaUrlForPath('register')
    );
})->name('register');

if (config('laravel-spa.check_email_exists_endpoint')) {
    Route::middleware('web')->post(
        config('laravel-spa.route_paths.email_exists'),
        // Even though it seems we don't use the User variable here, we need to have it so that Laravel can resolve the user variable in the path
        function (Request $request, User $user) {
            return response()->noContent(200);
        }
    );
}

Route::middleware('api', 'auth:sanctum')->prefix('api')->group(function () {
    Route::get('user', function (Request $request) {
        return $request->user();
    });
});

Route::middleware('web', 'auth:sanctum')->group(function () {
    Route::get('user/personal-access-tokens', function (Request $request) {
        return $request->user()->tokens;
    });

    Route::post('user/personal-access-tokens', function (Request $request) {
        $token = $request->user()->createToken($request->token_name);
        return ['token' => $token->plainTextToken];
    });

    Route::delete('user/personal-access-tokens/{token_id}', function (Request $request, $tokenId) {
        $request->user()->tokens()->where('id', $tokenId)->delete();
        return response()->noContent();
    });
});
