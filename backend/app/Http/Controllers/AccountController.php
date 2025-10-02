<?php

namespace App\Http\Controllers;

use App\Http\Services\AccountService;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct(
        private AccountService $service
    ) {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->index();
    }

    public function show(string $id)
    {
        return $this->service->show($id);
    }

    public function store(Request $request)
    {
        return $this->service->store($request);
    }

    public function update(Request $request, string $id)
    {
        return $this->service->update($request, $id);
    }

    public function delete(string $id)
    {
        return $this->service->delete($id);
    }

    public function defineDefault(string $id)
    {
        return $this->service->defineDefault($id);
    }
}
