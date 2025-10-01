<?php

namespace App\Http\Controllers;

use App\Http\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $service
    ) {
        $this->service = $service;
    }

    public function register(Request $request)
    {
        return $this->service->register($request);
    }

    public function login(Request $request)
    {
        return $this->service->login($request);
    }

    public function changePassword(Request $request, string $token = null)
    {
        return $this->service->changePassword($request, $token);
    }

    public function resetPassword(Request $request)
    {
        return $this->service->resetPassword($request);
    }

    public function me()
    {
        return $this->service->me();
    }

    public function logout()
    {
        return $this->service->logout();
    }

    public function refresh(Request $request)
    {
        return $this->service->refresh($request);
    }
}
