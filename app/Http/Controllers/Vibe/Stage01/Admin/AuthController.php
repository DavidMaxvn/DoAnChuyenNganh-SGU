<?php

namespace App\Http\Controllers\Vibe\Stage01\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vibe\Stage01\Admin\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function info(): JsonResponse
    {
        return response()->json([
            'stage' => 'stage-01',
            'module' => 'admin-auth',
            'message' => 'Stage 01 admin auth backend is ready.',
            'endpoints' => [
                'login' => route('vibe.stage01.admin.login'),
                'dashboard' => route('vibe.stage01.admin.dashboard'),
                'products' => route('vibe.stage01.admin.products.index'),
            ],
        ]);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);
        $remember = $request->boolean('remember');

        if (!Auth::guard('admin')->attempt($credentials, $remember)) {
            return response()->json([
                'message' => 'Email hoac mat khau khong dung.',
            ], 422);
        }

        $request->session()->regenerate();

        $admin = Auth::guard('admin')->user();

        return response()->json([
            'message' => 'Dang nhap admin thanh cong.',
            'data' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
            ],
        ]);
    }

    public function dashboard(Request $request): JsonResponse
    {
        $admin = $request->user('admin');

        return response()->json([
            'stage' => 'stage-01',
            'message' => 'Admin dashboard backend is ready.',
            'data' => [
                'admin' => [
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'email' => $admin->email,
                ],
                'capabilities' => [
                    'admin_login',
                    'product_data_entry',
                    'product_listing',
                ],
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Dang xuat admin thanh cong.',
        ]);
    }
}
