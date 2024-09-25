<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $user = Auth::user();

            $token = $user->createToken('Personal Access Token')->accessToken;

            Log::info('Успешный вход', ['username' => $request->username]);

            return response()->json([
                'success' => true,
                'token' => $token,
            ]);
        }

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
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        Log::info('Пользователь вышел из системы', [
            'user_id' => $request->user()->id,
            'email' => $request->user()->email,
            'time' => now(),
        ]);

        $token = $request->user()->token();
        $token->revoke();

        return response()->json(['message' => 'Выход выполнен успешно']);
    }

}
