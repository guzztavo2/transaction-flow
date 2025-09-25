<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TransactionTest
{
    #[Test]
    public function test_initialization()
    {
        //test initialization
    }

    public function deposit(){
        $response = $this->postJson('api/auth/register', $this->pessoal_information_to_test);
    }
}