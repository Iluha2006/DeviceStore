<?php
namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'required' => 'Поле :attribute обязательно для заполнения',
            'email' => 'Введите корректный email адрес',
            'unique' => 'Этот email уже занят',
            'min' => 'Пароль должен содержать минимум :min символов',
            'confirmed' => 'Пароли не совпадают',
            'max' => 'Поле :attribute не должно превышать :max символов'
        ], [
            'name' => 'Имя',
            'email' => 'Email',
            'password' => 'Пароль',
            'password_confirmation' => 'Подтверждение пароля'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Ошибка данных',
                'errors' => $validator->errors()->messages()
            ], 422);
        }

        $user = User::create(
[
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        if (!$user->profile) {
            $user->profile()->create([
                'name' => $user->name,
                'email' => $user->email,

            ]);
        }

        $token = $user->createToken('auth-token')->plainTextToken;
        return $this->authService->respondWithToken($token, $user);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Поле Email обязательно для заполнения',
            'email.email' => 'Введите корректный email адрес',
            'password.required' => 'Поле Пароль обязательно для заполнения'
        ]);

        $result = $this->authService->attemptLogin($request);
        if (!$result) {
            return response()->json(['error' => 'Неверные учетные данные'], 401);
        }

        $user = $result['user'];
        if (!$user->profile) {
            $user->profile()->create([
                'name' => $user->name,
                'email' => $user->email,
         
            ]);
        }

        return $this->authService->respondWithToken($result['token'], $user);
    }

    public function me()
    {
        $user = $this->authService->getAuthenticatedUser();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return response()->json($user);
    }

    public function logout()
    {
        $user = auth()->user();
        if ($user) {
            $user->tokens()->delete();
        }

        $cookie = Cookie::forget('laravel-sanctum');
        return response()
            ->json(['message' => 'Logged out'])
            ->withCookie($cookie);
    }
}