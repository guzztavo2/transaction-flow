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
        return $this->service->register($request);
    }

    public function login(Request $request)
    {
        return $this->service->login($request);
    }

    public function changePassword(Request $request)
    {
        return $this->service->changePassword($request);
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
  
    public function refresh()
    {
        return $this->service->refresh();
    }
 
}
