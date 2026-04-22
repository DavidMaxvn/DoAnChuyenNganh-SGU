<?php

namespace App\Http\Controllers\Vibe\Stage04;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vibe\Stage04\ForgotPasswordRequest;
use App\Http\Requests\Vibe\Stage04\ResetPasswordRequest;
use App\Services\Vibe\Stage04\UserAccountService;
use Illuminate\Http\JsonResponse;

class PasswordResetController extends Controller
{
    public function __construct(protected UserAccountService $accountService)
    {
    }

    public function forgot(ForgotPasswordRequest $request): JsonResponse
    {
        $resetPreview = $this->accountService->createPasswordResetToken($request->validated('email'));

        return response()->json([
            'message' => 'Da tao token quen mat khau de giao dien hoac mail layer su dung.',
            'data' => $resetPreview,
        ]);
    }

    public function reset(ResetPasswordRequest $request): JsonResponse
    {
        $this->accountService->resetPassword($request->validated());

        return response()->json([
            'message' => 'Dat lai mat khau thanh cong.',
        ]);
    }
}
