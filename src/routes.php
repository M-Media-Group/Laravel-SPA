<?php
// Even though it seems that the User is not used here in the callback function, we need to have it so that Laravel can resolve the user variable in the path

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Mmedia\LaravelSpa\LaravelSpaFacade;

if (config('laravel-spa.check_email_exists_endpoint')) {
    Route::middleware('web')->post(
        config('laravel-spa.api_paths.email_exists'),
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

    if (config('laravel-spa.allow_creating_personal_access_tokens')) {
        Route::post('user/personal-access-tokens', function (Request $request) {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);
            $token = $request->user()->createToken($request->name);
            return [
                ...$token->accessToken->toArray(),
                'token' => $token->plainTextToken
            ];
        });
    }

    Route::delete('user/personal-access-tokens/{token_id}', function (Request $request, $tokenId) {
        $request->user()->tokens()->where('id', $tokenId)->delete();
        return response()->noContent();
    });
});
