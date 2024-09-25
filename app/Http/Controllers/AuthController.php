<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Обрабатывает вход пользователя.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $limiterKey = 'login-attempt-' . $request->ip();
        if (RateLimiter::tooManyAttempts($limiterKey, 5)) {
            return response()->json(['message' => 'Слишком много попыток входа в систему. Попробуйте еще раз позже.'], 429);
        }

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('Personal Access Token')->accessToken;

            Log::info('Успешный вход', ['username' => $request->username]);

            RateLimiter::clear($limiterKey); // Reset attempts after successful login.

            return response()->json([
                'success' => true,
                'token' => $token,
            ]);
        }

        RateLimiter::hit($limiterKey); // Increment attempts on failure.
        Log::warning('Неудачный вход', ['username' => $request->username]);

        return response()->json(['success' => false, 'message' => 'Неверные учетные данные'], 401);
    }

    /**
     * Обрабатывает выход пользователя.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $token = $request->user()->token();
        if ($token) {
            $token->revoke();
            Log::info('Пользователь вышел из системы', [
                'user_id' => $request->user()->id,
                'email' => $request->user()->email,
                'time' => now(),
            ]);
            return response()->json(['message' => 'Выход выполнен успешно']);
        }

        return response()->json(['message' => 'Токен не найден'], 400);
    }

}
