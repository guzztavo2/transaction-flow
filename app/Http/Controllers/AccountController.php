<?php

namespace App\Http\Controllers;

use App\Http\Services\AuthService;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct(
        private AccountService $service
    ) {
        $this->service = $service;
    }

    public function accounts()
    {
        return $this->service->accounts();
    }

    public function account(string $id)
    {
        return $this->service->account($id);
    }

    public function create(Request $request)
    {
        return $this->service->create($request);
    }

    public function update(Request $request, string $id)
    {
        return $this->service->update($request, $id);
    }

    public function delete(string $id)
    {
        return $this->service->delete($id);
    }

    public function defineDefaultAccount(string $id)
    {
        return $this->service->defineDefaultAccount($id);
    }
}
