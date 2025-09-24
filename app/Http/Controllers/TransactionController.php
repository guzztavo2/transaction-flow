<?php

namespace App\Http\Controllers;

use App\Http\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(
        private TransactionService $service
    ) {
        $this->service = $service;
    }

    public function store(Request $request){

    }
    
}
