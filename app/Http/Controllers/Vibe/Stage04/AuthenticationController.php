<?php

namespace App\Http\Controllers\Vibe\Stage04;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vibe\Stage04\LoginRequest;
use App\Http\Requests\Vibe\Stage04\RegisterRequest;
use App\Services\Vibe\Stage04\UserAccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthenticationController extends Controller
{
    public function __construct(protected UserAccountService $accountService)
    {
    }

    public function overview(): JsonResponse
    {
        return response()->json([
            'message' => 'Stage 04 tap trung vao auth user, profile, quen mat khau va social login.',
            'data' => $this->accountService->overview(),
        ]);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->accountService->register($request->validated());

        return response()->json([
            'message' => 'Dang ky thanh cong. Nguoi dung co the chuyen sang buoc dang nhap.',
            'data' => [
                'user' => $this->accountService->toProfileArray($user),
                'next_action' => 'login',
            ],
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = $this->accountService->login(
            $request->validated(),
            $request->boolean('remember')
        );

        $request->session()->regenerate();

        return response()->json([
            'message' => 'Dang nhap thanh cong.',
            'data' => [
                'user' => $this->accountService->toProfileArray($user),
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->accountService->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Dang xuat thanh cong.',
        ]);
    }
}
