<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }


    public function login(LoginRequest $request)
    {
        $request->ensureIsNotRateLimited();

        if (!$token = Auth::attempt($request->all())) {
            $request->increaseRateLimiter();

            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->resetRateLimiter();

        return $this->createNewToken($token);
    }

    public function logout(Request $request)
    {
        /**
         * @var User $user
         */
        $user = $request->user();

        $user->currentAccessToken()->delete();

        event(new \Illuminate\Auth\Events\Logout('sanctum', $user));

        return response()->noContent();
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => Auth::user()
        ]);
    }
}
