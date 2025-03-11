<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\AuthService;

class AuthController extends Controller
{
    private AuthService $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }
    public function register(Request $request)
    {
        $this->service->register($request->toArray());
    }

    public function login(Request $request)
    {
        $this->service->login($request->toArray());
    }

    public function changePassword(Request $request)
    {
        $this->service->changePassword($request->toArray());
    }

    public function resetPassword(Request $request)
    {
        $this->service->resetPassword($request->toArray());
    }
}
