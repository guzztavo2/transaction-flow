<?php

namespace App\Http\Controllers;

use App\Http\Services\AuthService;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    private AuthService $service;

    public function __construct(AccountService $service)
    {
        $this->service = $service;
    }
}
