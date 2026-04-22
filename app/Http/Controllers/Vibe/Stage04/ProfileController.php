<?php

namespace App\Http\Controllers\Vibe\Stage04;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vibe\Stage04\ProfileUpdateRequest;
use App\Models\User;
use App\Services\Vibe\Stage04\UserAccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(protected UserAccountService $accountService)
    {
    }

    public function show(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user('web');

        return response()->json([
            'message' => 'Thong tin tai khoan hien tai.',
            'data' => [
                'user' => $this->accountService->toProfileArray($user),
            ],
        ]);
    }

    public function update(ProfileUpdateRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user('web');
        $user = $this->accountService->updateProfile($user, $request->validated());

        return response()->json([
            'message' => 'Cap nhat profile thanh cong.',
            'data' => [
                'user' => $this->accountService->toProfileArray($user),
            ],
        ]);
    }
}
