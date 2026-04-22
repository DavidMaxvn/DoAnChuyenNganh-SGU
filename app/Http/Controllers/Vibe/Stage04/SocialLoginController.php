<?php

namespace App\Http\Controllers\Vibe\Stage04;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vibe\Stage04\SocialCallbackRequest;
use App\Services\Vibe\Stage04\UserAccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SocialLoginController extends Controller
{
    public function __construct(protected UserAccountService $accountService)
    {
    }

    public function callback(SocialCallbackRequest $request): JsonResponse
    {
        $result = $this->accountService->socialLogin($request->validated());

        $request->session()->regenerate();

        return response()->json([
            'message' => 'Social login thanh cong.',
            'data' => $result,
        ]);
    }
}
